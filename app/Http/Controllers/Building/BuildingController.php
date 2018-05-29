<?php

namespace App\Http\Controllers\Building;

use App\Http\Requests\BuildingPost;
use App\Models\Building;
use App\Models\Resource;
use App\Service\BuildingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class BuildingController extends Controller
{
    const RETURN_RATE = 0.35;

    public function __construct(BuildingService $buildingService)
    {
        $this->middleware('auth');
        $this->buildingService = $buildingService;
    }

    public function buildingList($version)
    {
        if ($version !== config('params.version')) {
            $list = json_decode(Redis::get('buildingList'), true);
            $list['version'] = config('params.version');

            return $list;
        }

        return response('客户端版本不符，无法获取相关数据',304);
    }

    public function build(BuildingPost $post)
    {
        $post->number = $post->number ?? 1;
        $item = json_decode(Redis::get('buildingList'), true);
        if (!$item) {
            // todo: write log.
        }
        $item = $item[$post->type][$post->level - 1];

        // 检测资源数量
        $resource = Resource::where('userId', Auth::id())->first();

        // 扣除资源
        foreach ($item['material'] as $key => $value) {
            $deplete = $value * $post->number;
            if ($resource->$key < $deplete)
                return [ 201, '资源不足' ];

            $resource->$key -= $deplete;
        }

        // 扣除占用
        foreach ($item['occupy'] as $key => $value) {
            $deplete = $value * $post->number;
            if ($resource->$key < $deplete)
                return [ 202, '资源不足' ];

            $resource->$key -= $deplete;
        }

        // 增加建筑数量
        $building = Building::where('userId', Auth::id())->first();

        if ($post->level < 10)
            $post->level = '0' . $post->level;
        $buildingName = $post->type . $post->level;

        $building->$buildingName += $post->number;

        // 增加产出
        foreach ($item['product'] as $key => $value) {
            $itemName = $key . 'Output';
            $resource->$itemName += $value * $post->number;
        }

        DB::beginTransaction();
        try {
            if ($resource->save() && $building->save()) {
                DB::commit();
                return [ 101, '建筑完成' ]; // todo: 任务队列
            } else {
                DB::rollBack();
                return [ 203, '因未预料的意外，建筑失败' ];
            }
        } catch (\Exception $exception) {
            // todo: write log
            $logID = 'Bbs' . 1;
            return response('意外情况，编号：' . $logID, 500);
        }
    }

    public function destroy(BuildingPost $post)
    {
        $post->number = $post->number ?? 1;
        $item = json_decode(Redis::get('buildingList'), true);
        if (!$item) {
            // todo: write log.
        }
        $item = $item[$post->type][$post->level - 1];
        // 检测建筑数量，数量满足则扣除建筑
        $building = Building::where('userId', Auth::id())->first();

        if ($post->level < 10)
            $post->level = '0' . $post->level;
        $buildingName = $post->type . $post->level;

        if ($building->$buildingName < $post->number) {
            return [ 201, '建筑数量不足' ];
        }
        $building->$buildingName -= $post->number;

        $resource = Resource::where('userId', Auth::id())->first();
        // 降低产出
        foreach ($item['product'] as $key => $value) {
            $itemName = $key . 'Output';
            $resource->$itemName -= $value * $post->number;
        }

        // 降低（解除）占用
        foreach ($item['occupy'] as $key => $value) {
            $resource->$key += $value * $post->number;
        }

        // 增加资源
        foreach ($item['material'] as $key => $value) {
            $resource->$key += intval($value * $post->number * self::RETURN_RATE);
        }

        DB::beginTransaction();
        try {
            if ($resource->save() && $building->save()) {
                DB::commit();
                return [ 101, '拆除完成' ]; // todo: 任务队列
            } else {
                DB::rollBack();
                return [ 203, '因未预料的意外，拆除失败' ];
            }
        } catch (\Exception $exception) {
            // todo: write log
            $logID = 'Bds' . 1;
            return response('意外情况，编号：' . $logID, 500);
        }
    }
}
