<?php

namespace App\Service;

use App\Models\Building;
use App\Models\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class BuildingService
{
    // 建筑队数量
    const CONSTRUCTION_MAX = 3;
    // 拆除时，回收的资源比例
    const RETURN_RATE = 0.35;
    // 取消建造时，放弃的已投入资源比例
    const ABANDON_RATE = 0.30;

    protected function getBuildingList()
    {
        $buildingList = json_decode(Redis::get('buildingList'), true);
        if (!$buildingList) {
            logService::common('未获取到 Redis 缓存的建筑列表', 404, '\App\Http\Controllers\Building\BuildingController::build', 'Error');
        }

        return $buildingList;
    }

    /**
     * 建筑队动工，扣住资源、占用
     *
     * @version 0.01
     * @param $type
     * @param $level
     * @param $number
     * @return array
     */
    public function buildBefore($type, $level, $number)
    {
        // 检查施工队数量、操作建筑数量
        $key = getUserKey('buildList');
        $schedule = json_decode(Redis::get($key), true);

        if (count($schedule) >= self::CONSTRUCTION_MAX) {
            return ['status' => 'error', 'info' => '施工队都在忙碌'];
        }

        $startTime = time();
        // 计算结束时间
        $item = $this->getBuildingList();
        $item = $item[$type][$level - 1];
        $endTime = $startTime + $item['time'] * $number;

        // 完成时间戳 + 开始时间戳 + 类别 + 级别 + 数量
        $name = [
            $endTime,
            $startTime,
            $type,
            $level,
            $number,
        ];
        $schedule[] = implode('-', $name);
        $result = Redis::set($key, json_encode($schedule));

        $resource = Resource::where('userId', Auth::id())->first();
        if ($result) {
            // 扣除资源
            foreach ($item['material'] as $key => $value) {
                $deplete = $value * $number;
                if ($resource->$key < $deplete)
                    return [ 201, '资源不足（消耗类）' ];

                $resource->$key -= $deplete;
            }

            // 扣除占用
            foreach ($item['occupy'] as $key => $value) {
                $deplete = $value * $number;
                if ($resource->$key < $deplete)
                    return [ 202, '资源不足（占用类）' ];

                $resource->$key -= $deplete;
            }
        }

        DB::beginTransaction();
        try {
            if ($resource->save()) {
                DB::commit();
                return [ 101, '施工队已经开始工作' ];
            } else {
                DB::rollBack();
                return [ 203, '因为意外，建筑队未能启动工作' ];
            }
        } catch (\Exception $exception) {
            $logID = 'BbbBT' . logService::common('拆除失败', 500, '\App\Service\BuildingService::destroy', 'Error');
            return response('意外情况，编号：' . $logID, 500);
        }
    }

    /**
     * 取消建造建筑，返还部分资源，解除占用
     *
     * @version 0.01
     * @param string $name
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|string|\Symfony\Component\HttpFoundation\Response
     */
    public function buildRecall(string $name)
    {
        $key = getUserKey('buildList');
        $schedules = json_decode(Redis::get($key), true);

        if (count($schedules) === 0) {
            return [101, '施工队都在忙碌'];
        } elseif (empty($schedule[$name])) {
            return [101, '该施工项目已完成'];
        }

        $schedule = exploreSchedule($schedules, $name);
        $item = $this->getBuildingList();
        $item = $item[$schedule['type']][$schedule['level'] - 1];
        $resource = Resource::where('userId', Auth::id())->first();
        // 返还部分资源
        foreach ($item['material'] as $key => $value) {
            $resource->$key += intval($value * $schedule['number'] * (1 - self::ABANDON_RATE));
        }

        // 解除占用
        foreach ($item['occupy'] as $key => $value) {
            $resource->$key += $value * $schedule['number'];
        }

        DB::beginTransaction();
        try {
            if (!$resource->save()) {
                DB::rollBack();
                return [ 203, '因为意外，建筑队未能终止工作' ];
            }

            // 删除建筑进程
            foreach ($schedule as $key => $value) {
                if ($value === $name) {
                    unset($schedule[$key]);
                    break;
                }
            }
            $result = Redis::set($key, json_encode($schedule));
            if (!$result) {
                DB::rollBack();
                return [ 203, '因为意外，建筑队未能终止工作' ];
            }

            DB::commit();
            return [ 101, '已取消该施工项目' ];
        } catch (\Exception $exception) {
            $logID = 'JeihC' . logService::common('取消失败', 500, '\App\Service\BuildingService::buildRecall', 'Error');
            return response('意外情况，编号：' . $logID, 500);
        }
    }

    /**
     * 建造建筑，增加建筑数量、生产资源量
     *
     * @param $number
     * @param $type
     * @param $level
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function build($type, $level, $number)
    {
        $number = $number ?? 1;
        $item = $this->getBuildingList();
        $item = $item[$type][$level - 1];

        $resource = Resource::where('userId', Auth::id())->first();
        $building = Building::where('userId', Auth::id())->first();

        // 增加建筑数量
        if ($level < 10)
            $level = '0' . $level;
        $buildingName = $type . $level;
        $building->$buildingName += $number;

        // 增加产出
        foreach ($item['product'] as $key => $value) {
            $itemName = $key . 'Output';
            $resource->$itemName += $value * $number;
        }

        DB::beginTransaction();
        try {
            if ($resource->save() && $building->save()) {
                DB::commit();
                return [ 101, '建筑完成' ];
            } else {
                DB::rollBack();
                return [ 203, '因未预料的意外，建筑失败' ];
            }
        } catch (\Exception $exception) {
            $logID = 'Bbs' . logService::common('建筑失败', 500, '\App\Http\Controllers\Building\BuildingController::destroy', 'Error');
            return response('意外情况，编号：' . $logID, 500);
        }
    }

    /**
     * 拆除建筑，返还部分资源，解除占用，降低建筑数量、降低产出
     *
     * @param $type
     * @param $level
     * @param $number
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($type, $level, $number)
    {
        $number = $number ?? 1;
        $item = $this->getBuildingList();
        $item = $item[$type][$level - 1];

        $resource = Resource::where('userId', Auth::id())->first();
        $building = Building::where('userId', Auth::id())->first();

        // 降低建筑数量
        if ($level < 10)
            $level = '0' . $level;
        $buildingName = $type . $level;
        $building->$buildingName -= $number;

        // 返还部分资源
        foreach ($item['material'] as $key => $value) {
            $resource->$key += intval($value * $number * self::RETURN_RATE);
        }

        // 解除占用
        foreach ($item['occupy'] as $key => $value) {
            $resource->$key += $value * $number;
        }

        // 降低产出
        foreach ($item['product'] as $key => $value) {
            $itemName = $key . 'Output';
            $resource->$itemName -= $value * $number;
        }

        DB::beginTransaction();
        try {
            if ($resource->save() && $building->save()) {
                DB::commit();
                return [ 101, '拆除完成' ];
            } else {
                DB::rollBack();
                return [ 203, '因未预料的意外，拆除失败' ];
            }
        } catch (\Exception $exception) {
            $logID = 'BdC2c' . logService::common('拆除失败', 500, '\App\Service\BuildingService::destroy', 'Error');
            return response('意外情况，编号：' . $logID, 500);
        }
    }
}
