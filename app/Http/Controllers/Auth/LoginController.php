<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Step;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/home';
    /*
    |--------------------------------------------------------------------------
    | 登录成功后的逻辑
    |--------------------------------------------------------------------------
    */
    protected function authenticated(Request $request, $user)
    {
        // 初始化 current_step_id（如果为空）
        if (!$user->current_step_id) {
            $user->update([
                'current_step_id' => Step::orderBy('order')->value('id'),
            ]);
        }

        // 重定向到主页
        return redirect()->intended(route('home'));
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 设置登录成功后的重定向路由
    public function redirectTo()
    {
        return route('home');
    }
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }
}
