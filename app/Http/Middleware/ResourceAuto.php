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
    const FERTILITY_RATE = 0.042;
    // 饥荒死亡率（月）
    const STARVE_RATE = 0.06;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
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
    protected function resourceUpdate() {
        $manpower = 1;
        $necessary = 1;
        $peopleOccupy = [
            'manpower' => 0,
            'necessary' => 0,
        ];

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
            $item = $list[$field][$level-1];
            $item['occupy']['people'] = $item['occupy']['people'] ?? 0;
            if ($item['necessary']) {
                $peopleOccupy['necessary'] += $item['occupy']['people'] * $value;
            } else {
                $peopleOccupy['manpower'] += $item['occupy']['people'] * $value;
            }
        }

        $resource = Resource::find(['userId' => Auth::id()])[0];

        if ($peopleOccupy['necessary'] >= $resource->people) {
            $necessary = $resource->people / $peopleOccupy['necessary'];
            $manpower = 0;
        } elseif ($peopleOccupy['manpower'] >= $resource->people - $peopleOccupy['necessary']) {
            $manpower = ($resource->people - $peopleOccupy['necessary']) / $peopleOccupy['necessary'];
        }

        // 资源叠加
        $resource->food += $resource->foodOutput * $necessary - $resource->people * 0.1;

        $people = explode('.', $resource->people * self::FERTILITY_RATE);
        $resource->people += $people[0];
        $resource->child += $people[1];
        if ($resource->food <= 0) {
            $resource->food = 0;
            $resource->child = 0;
            $resource->people -= $resource->people * self::STARVE_RATE;
        } elseif ($resource->child >= 1) {
            $people = explode('.', $resource->child)[0];
            $resource->people += $people;
            $resource->child -= $people;
        }

        $resource->wood += $resource->woodOutput * $manpower;
        $resource->stone += $resource->stoneOutput * $manpower;
        $resource->money += $resource->moneyOutput * $manpower + $resource->people * 0.1;

        $resource->save();
    }
}
