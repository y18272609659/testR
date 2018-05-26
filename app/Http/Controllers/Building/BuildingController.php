<?php

namespace App\Http\Controllers\Building;

use App\Service\BuildingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class BuildingController extends Controller
{
    public function __construct(BuildingService $buildingService)
    {
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
}
