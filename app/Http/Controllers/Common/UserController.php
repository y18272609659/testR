<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\BuildingList;
use App\Models\Resource;
use App\Models\System;
use App\Service\LogService;
use App\User;
use App\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userService;
    protected $logService;

    public function __construct(UserService $userService, LogService $logService)
    {
        $this->userService = $userService;
        $this->logService = $logService;
    }

    /**
     * 用户注册
     *
     * @param Request $request
     * @return array
     */
    public function register(Request $request)
    {
        $m = new BuildingList();
        $check = User::where('email', $request['email'])->exists();

        if (!$check) {
            $userPlant = config('params.userPlant');
            $lastId = User::select('id')->orderby('id', 'desc')->first();
            $id = $lastId->id + 1;
            $capital = ceil($id / sqrt($userPlant)) * 3 - 1 . ',' . ((($id - 1) % sqrt($userPlant) + 1) * 3 - 1);

            DB::beginTransaction();
            try {
                $info['user'] = User::create([
                    'nickname' => $request['nickname'],
                    'email' => $request['email'],
                    'kingdom' => $request['kingdom'],
                    'capital' => $capital,
                    'password' => Hash::make($request['password']),
                ]);

                BuildingList::create([
                    'userId' => $info['user']->id,
                    'start' => 0,
                    'end' => 0,
                    'category' => '',
                    'level' => 0,
                    'action' => 0,
                    'number' => 0,
                ], [
                    'userId' => $info['user']->id,
                    'start' => 0,
                    'end' => 0,
                    'category' => '',
                    'level' => 0,
                    'action' => 0,
                    'number' => 0,
                ], [
                    'userId' => $info['user']->id,
                    'start' => 0,
                    'end' => 0,
                    'category' => '',
                    'level' => 0,
                    'action' => 0,
                    'number' => 0,
                ]);

                $this->logService::signUpOrIn('注册成功', 101, false);

                $info['user'] = User::find($info['user']->id);
                $this->userService->initRedis();

                $info['building'] = Building::create([
                    'userId' => $info['user']->id,
                ]);
                $info['building'] = Building::find($info['building']->id);

                $info['resource'] = Resource::create([
                    'userId' => $info['user']->id,
                ]);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                $logID = 'UriBT' . logService::common('拆除失败', 500, '\App\Http\Controllers\Common\UserController::register', 'Error');
                return response('意外情况，编号：' . $logID, 500);
            }
            $info['resource'] = Resource::find($info['resource']->id);

            $info['system'] = System::orderBy('id', 'desc')->take(1)->get();

            return $info;
        }

        return response('帐号已存在，找回它，或换一个吧', 400);
    }

    /**
     * 用户登录
     *
     * @param Request $request
     * @return array
     */
    public function login(Request $request)
    {
        if (Auth::check() || Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            $this->logService::signUpOrIn('登录成功', 101);
            $info['user'] = User::find(Auth::id());

            if (!$systemInfo = Redis::get('systemInfo')) {
                $systemInfo = json_encode(System::orderBy('id', 'desc')->first());
                Redis::set('systemInfo', $systemInfo);
            }
            $info['system'] = json_decode($systemInfo);

            $info['resource'] = Resource::where('userId', Auth::id())->first();
            $info['building'] = Building::where('userId', Auth::id())->first();

            $this->userService->checkRedis();

            return $info;
        }

        return response('帐号或密码错误，请检查后重试', 400);
    }

    /**
     * 用户注销
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();
        Session::flush();

//        return redirect('/');
    }
}
