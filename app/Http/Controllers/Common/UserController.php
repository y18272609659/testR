<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Resource;
use App\User;
use App\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(UserService $UserService)
    {
        $this->UserService = $UserService;
    }

    /**
     * 用户注册
     *
     * @param Request $request
     * @return array
     */
    public function register(Request $request)
    {
        $check = User::where('email', $request['email'])->exists();

        if (!$check) {
            $userPlant = config('params.userPlant');
            $lastId = User::select('id')->orderby('id', 'desc')->first();
            $id = $lastId->id + 1;
            $capital = ceil($id / sqrt($userPlant)) * 3 - 1 . ',' . ((($id - 1) % sqrt($userPlant) + 1) * 3 - 1);
            $info['user'] = User::create([
                'nickname' => $request['nickname'],
                'email' => $request['email'],
                'kingdom' => $request['kingdom'],
                'capital' => $capital,
                'password' => Hash::make($request['password']),
            ]);
            $info['user'] = User::find($info['user']->id);

            $info['building'] = Building::create([
                'userId' => $info['user']->id,
            ]);
            $info['building'] = Building::find($info['building']->id);

            $info['resource'] = Resource::create([
                'userId' => $info['user']->id,
            ]);
            $info['resource'] = Resource::find($info['resource']->id);

            return [ 101, $info ];
        }

        return [ 201, '帐号已存在，找回它，或换一个吧。' ];
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
            $model['user'] = User::find(Auth::id());
            $model['resource'] = Resource::where('userId', Auth::id())->first();
            $model['building'] = Building::where('userId', Auth::id())->first();

            return [ 101, $model ];
        }

        return [ 201, '帐号或密码错误，请检查后重试。' ];
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

        return redirect('/');
    }
}
