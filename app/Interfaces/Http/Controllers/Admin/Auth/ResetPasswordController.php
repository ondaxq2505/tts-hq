<?php

namespace App\Interfaces\Http\Controllers\Admin\Auth;

use App\Infrastructure\Persistence\Models\User;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Core\Application\Services\AuthService;
use Carbon\Carbon;
use App\Interfaces\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    protected AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->middleware('guest')->except('logout');
        $this->authService = $authService;
    }
    public function showResetForm(Request $request, $token = null)
    {
        $email = $request->email ?? session('otp_user_email');

        if (!$token || !$email) {
            return redirect()->route('admin.login')->with('error', 'Liên kết không hợp lệ hoặc đã hết hạn.');
        }

        return view('admin.auth.passwords.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $result = $this->authService->resetPassword($request->only(
            'email', 'password', 'password_confirmation', 'token'
        ));
        
        if (!$result['success']) {
            return back()->withErrors(['email' => $result['message']]);
        }

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if ($user) {
            $customKey = 'login_attempts:' . Str::lower($user->username);
            $throttleKey = Str::lower($user->username);

            Cache::forget($customKey);
            RateLimiter::clear($throttleKey);
        }
        if ($user->account_type === 1) {
        return redirect()->route('admin.login')->with('success', 'Mật khẩu đã được đặt lại thành công.');
        }
        else{
            return redirect()->route('user.login')->with('success', 'Mật khẩu đã được đặt lại thành công.');
        }
    }

}
