<?php

namespace App\Interfaces\Http\Controllers\User\Auth;

use App\Infrastructure\Persistence\Models\User;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Core\Application\Services\AuthService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Interfaces\Http\Requests\Auth\ResetPasswordRequest;

class ResetPasswordController extends Controller
{
    protected AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->middleware('guest')->except('logout');
        $this->authService = $authService;
    }
    public function showResetForm()
    {
        $email = session('otp_user_email');
        return view('user.auth.passwords.reset-password', ['email' => $email]);
    }
    public function reset(ResetPasswordRequest $request)
    {
        $email = $request->email;
    
        $this->authService->resetPassword($email, $request->password);
    
        $user = User::where('email', $email)->first();
    
        $customKey = 'login_attempts:' . Str::lower($user->username);
        Cache::forget($customKey);
    
        $throttleKey = Str::lower($user->username);
        RateLimiter::clear($throttleKey);
        dd($customKey, $throttleKey, Cache::get($customKey), RateLimiter::tooManyAttempts($throttleKey, 5));

        return redirect()->route('user.login')->with('status', 'Mật khẩu đã được đặt lại thành công!');
    }
    
}
