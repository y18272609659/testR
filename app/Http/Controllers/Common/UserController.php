<?php
namespace App\Http\Controllers\Common;

use App\Events\Test;
use App\Http\Controllers\Controller;
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
     * @return string
     */
    public function register(Request $request)
    {
        $check = User::where('email', $request['email'])->exists();

        if (!$check) {
            $result = User::create([
                'name' => $request['nickname'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);
        } else {
            $result = '帐号已存在，您可以尝试找回密码。';
        }

        return $result;
    }

    /**
     * 用户登录
     *
     * @param Request $request
     * @return string
     */
    public function login(Request $request)
    {
        if (Auth::check() || Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            $model = User::find(Auth::id());
            if (event(new Test($model))) {
                return $this->UserService->getUser(Auth::id());
            } else {
                response('意外的错误，错误码：GO5WS1', 500);
            }
        }

        return '帐号或密码错误，请检查后重试。';
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
