<?php

namespace App\Http\Middleware;

use App\Models\Building;
use App\Models\Resource;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ResourceAuto
{
    // 生育率（月）
    const FERTILITY_RATE = 1.042;
    // 饥荒死亡率（月）
    const STARVE_RATE = 0.94;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            $response = $next($request);
            $this->resourceUpdate();
        } else {
            $this->resourceUpdate();
            $response = $next($request);
        }

        return $response;
    }

    /**
     * 资源计算并自增
     * 紧急劳动力满足系数：(当前实际农作劳力)/(当前所需农作劳力)；劳动力满足系数：(当前实际非农作劳力)/(当前所需非农作劳力)
     * 资源自增公式：
     *  - 食物：（各级产粮设施产能相加，乘以紧急劳动力满足系数）-（0.1 × 人口数）
     *  - 人口：每月最后一天，人口数量乘以 0.042(真实值为 0.012)，将整数与小数分别保存。小数在存在整数时分割，整数部分计入整数，小数部分继续保存。
     *  - 木材：各级伐木设施产能相加，乘以劳动力满足系数
     *  - 石块：各级采石设施产能相加，乘以劳动力满足系数
     *  - 钱财：（人口数量 * 0.1）+（各类商业设施产能相加，乘以劳动力满足系数）。
     */
    protected function resourceUpdate()
    {
        $resource = Resource::where('userId', Auth::id())->first();
        $via = gameTimeUnit(time() - strtotime($resource->updated_at));

        if ($via > 0.03333) {
            $manpower = 1;
            $necessary = 1;
            $peopleOccupy = [
                'manpower' => 0,
                'necessary' => 0,
            ];

            /* 劳动力系数计算 */
            // 计算劳动力使用
            $list = json_decode(Redis::get('buildingList'), true);
            if (!$list) {
                // 特殊情况，计入日志
            }

            $building = DB::select('SELECT * FROM buildings WHERE userId = ? LIMIT 1', [Auth::id()]) ?? new \stdClass();
            $building = json_decode(json_encode($building[0]), true);
            foreach ($building as $key => $value) {
                $level = (int)substr($key, -2, 2);
                if ($level === 0) {
                    continue;
                }
                $field = substr($key, 0, -2);

                // 计算劳动力实际需求
                $item = $list[$field][$level - 1];
                $item['occupy']['people'] = $item['occupy']['people'] ?? 0;
                if ($item['necessary']) {
                    $peopleOccupy['necessary'] += $item['occupy']['people'] * $value;
                } else {
                    $peopleOccupy['manpower'] += $item['occupy']['people'] * $value;
                }
            }

            // 计算劳动力系数
            if ($peopleOccupy['necessary'] >= $resource->people) {
                $necessary = $resource->people / $peopleOccupy['necessary'];
                $manpower = 0;
            } elseif ($peopleOccupy['manpower'] >= $resource->people - $peopleOccupy['necessary']) {
                $manpower = ($resource->people - $peopleOccupy['necessary']) / $peopleOccupy['necessary'];
            }

            /* 资源叠加 */
            $deplete = $resource->people * 0.1 * $via;
            // 食物
            $interim = $this->resourceUp([$resource->food, $resource->foodChip], $necessary, $resource->foodOutput, $via, 2, $deplete);
            $resource->food = $interim['int'];
            $resource->foodChip = $interim['chip'];

            // todo: 根据食物产出，抵消一定程度的饿死
            // 人口
            $initPeople = $resource->people;

            $people = exploreTwo($initPeople * pow(self::FERTILITY_RATE, $via));
            $resource->child += $people[1];
            if ($resource->food <= 0) {
                $resource->food = 0;
                $resource->child = 0;
                $resource->people = intval($initPeople * pow(self::STARVE_RATE, $via) + $initPeople - $people[0]);
            } else {
                $resource->people = $people[0];
                if ($resource->child >= 1) {
                    $people = exploreTwo($resource->child);
                    $resource->people += $people[0];
                    $resource->child = $people[1];
                }
            }

            // 木材
            $interim = $this->resourceUp([$resource->wood, $resource->woodChip], $manpower, $resource->woodOutput, $via);
            $resource->wood = $interim['int'];
            $resource->woodChip = $interim['chip'];

            // 石头
            $interim = $this->resourceUp([$resource->stone, $resource->stoneChip], $manpower, $resource->stoneOutput, $via);
            $resource->stone = $interim['int'];
            $resource->stoneChip = $interim['chip'];

            // 钱财
            $interim = $this->resourceUp([$resource->money, $resource->moneyChip], $manpower, $resource->moneyOutput, $via, 1, $deplete);
            $resource->money = $interim['int'];
            $resource->moneyChip = $interim['chip'];

            $resource->save();
        }
    }

    /**
     * 计算资源迭代后的实际内容
     * @param array $item 包含整数与碎片资源的数组
     * @param float $manpower (紧缺/非紧缺)劳动力系数
     * @param float $output 资源产出
     * @param float $via 时间长度
     * @param int $operate 操作，从 1 至 2 分别为加减。默认为 0，无运算
     * @param float $number 操作值，通过运算来计入相应的资源项
     * @return array
     */
    protected function resourceUp(array $item, float $manpower, float $output, float $via, int $operate = 0, float $number = 0)
    {
        $item['int'] = $item[0];
        $item['chip'] = $item[1];

        $interim = exploreTwo($output * $manpower * $via);
        $item['int'] += $interim[0] + intval($item['chip'] + $interim[1]);
        $item['chip'] += $interim[1];

        if (!$operate) {
            if ($item['chip'] >= 1) {
                $interim = exploreTwo($item['chip']);
                $item['int'] += $interim[0];
                $item['chip'] = $interim[1];
            }
        } else {
            // 启动运算
            $interim = exploreTwo($number);
            if ($operate === 1) {
                $item['int'] += $interim[0];
                $item['chip'] += $interim[1];

                if ($item['chip'] >= 1) {
                    $interim = exploreTwo($item['chip']);
                    $item['int'] += $interim[0];
                    $item['chip'] = $interim[1];
                }
            } elseif ($operate === 2) {
                $item['int'] -= $interim[0];
                $item['chip'] -= $interim[1];

                if ($item['chip'] >= 1 || $item['chip'] <= 0) {
                    $interim = exploreTwo($item['chip']);
                    $item['int'] += $interim[0];
                    $item['chip'] = $interim[1];

                    if ($item['chip'] < 0) {
                        $item['int'] -= 1;
                        $item['chip'] += 1;
                    }
                }
            }
        }

        return $item;
    }
}
