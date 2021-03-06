<?php

namespace App\Http\Controllers\Building;

use App\Http\Requests\BuildingPost;
use App\Service\BuildingService;
use App\Service\LogService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class BuildingController extends Controller
{
    protected $buildingService;
    protected $logService;

    public function __construct(LogService $logService, BuildingService $buildingService)
    {
        $this->middleware('auth');
        $this->buildingService = $buildingService;
        $this->logService = $logService;
    }

    /**
     * 获取建筑列表
     * 本接口需要校验客户端版本，针对本地 JS 的意外旧缓存情况
     *
     * @param $version
     * @return \Illuminate\Contracts\Routing\ResponseFactory|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function buildingList()
    {
        $list = json_decode(Redis::get('buildingList'), true);

        return $list;
    }

    /**
     * 建筑动工
     *
     * @param BuildingPost $post
     * @return array
     */
    public function build(BuildingPost $post)
    {
        return $this->buildingService->buildBefore($post->type, $post->level, $post->number);
    }

    /**
     * 检查建筑队列并执行
     * @return array
     */
    public function schedule()
    {
        $schedule = json_decode(Redis::get(getUserKey('buildList')), true);
        if (!$schedule)
            return [ 102, '队列为空' ];

        $schedule = exploreSchedule($schedule);
        foreach ($schedule as $item) {
            if ($item['endTime'] <= time()) {
                $result = $this->buildingService->build($item['type'], $item['level'], $item['number']);
                if ($result[0] >= 200)
                    return $result;
            }
        }

        return [101, $schedule];
    }

    /**
     * 取消建筑
     *
     * @param string $name 队列名称
     * @return array
     */
    public function recall($name)
    {
        return $this->buildingService->buildRecall(trim($name));
    }

    /**
     * 拆除建筑
     *
     * @param string $name 队列名称
     * @return array
     */
    public function destroy(string $name)
    {
        $schedules = json_decode(Redis::get(getUserKey('buildList')), true);
        if (count($schedules) === 0) {
            return [101, '施工队都在忙碌'];
        } elseif (empty($schedule[$name])) {
            return [101, '该施工项目已完成'];
        }
        $schedule = exploreSchedule($schedules, $name);

        return $this->buildingService->destroy($schedule['type'], $schedule['level'], $schedule['number']);
    }
}
