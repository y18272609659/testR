<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

class InitializeController extends Controller
{
    public function initRedis()
    {
        $buildingList = json_encode($this->getBuildingList());
        if (!Redis::set('buildingList', $buildingList))
            return response('任务失败，执行至 V846C2 阶段。', 500);
        $armyList = json_encode($this->getArmyList());
        if (!Redis::set('armyList', $armyList))
            return response('任务失败，执行至 V848VS 阶段。', 500);

        return response('任务执行成功', 200);
    }

    /**
     * 获取建筑清单
     * @param null $key
     * @return array|mixed
     */
    protected function getBuildingList($key = null)
    {
        $list = [
            'farm' => [
                [
                    'name' => '一级农田',
                    'level' => 1,
                    'necessary' => 1,
                    'product' => [
                        'food' => 1,
                    ],
                    'material' => [
                        'wood' => 10,
                    ],
                    'occupy' => [
                        'people' => 1,
                    ],
                ], [
                    'name' => '二级农田',
                    'level' => 2,
                    'necessary' => 1,
                    'product' => [
                        'food' => 1.2, // upper 20%
                    ],
                    'material' => [
                        'wood' => 13, // upper 30%
                    ],
                    'occupy' => [
                        'people' => 1,
                    ],
                ],
            ],
            'sawmill' => [
                [
                    'name' => '一级伐木营地',
                    'level' => 1,
                    'necessary' => 0,
                    'product' => [
                        'wood' => 0.6,
                    ],
                    'material' => [
                        'money' => 10,
                    ],
                    'occupy' => [
                        'people' => 1,
                    ],
                ], [
                    'name' => '二级伐木营地',
                    'level' => 2,
                    'necessary' => 0,
                    'product' => [
                        'wood' => 1.6, // upper 25%
                    ],
                    'material' => [
                        'money' => 28, // upper 40%
                    ],
                    'occupy' => [
                        'people' => 2,
                    ],
                ],
            ],
        ];
        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }

    /**
     * 获取军队清单
     * @param null $key
     * @return array|mixed
     */
    protected function getArmyList($key = null) {
        return [];
    }
}
