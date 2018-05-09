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

    public function buildingList()
    {
        $list = Redis::get('buildingList');

        return $list;
    }
}
