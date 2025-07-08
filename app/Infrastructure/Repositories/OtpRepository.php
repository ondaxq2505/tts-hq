<?php

namespace App\Infrastructure\Repositories;

use App\Core\Domain\Repositories\OtpRepositoryInterface;
use App\Infrastructure\Persistence\Models\Otp;
use Illuminate\Database\Eloquent\Model;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtp;
use Illuminate\Support\Facades\Password;

class OtpRepository implements OtpRepositoryInterface
{
    public function sendOtp(string $email, string $status): void
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Otp::updateOrCreate(
            ['email' => $email, 'status' => $status],
            [
                'code' => $code,
                'expires_at' => now()->addMinutes(2),
                'status' => $status,
            ]
        );
        Mail::to($email)->send(new SendOtp($email, $code));
    }
    public function sendResetLink(string $email): void
    {
        $status = Password::sendResetLink(['email' => $email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new \Exception('Không thể gửi link đặt lại mật khẩu. Vui lòng thử lại.');
        }
    }
    public function verifyOtp(string $otpCode): void
    {
        $user = Auth::user() ?? User::where('email', Session::get('otp_user_email'))->first();

        if (!$user) {
            throw new \Exception('Phiên xác thực không hợp lệ.');
        }

        $otp = Otp::where('email', $user->email)
            ->where('code', $otpCode)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            throw new \Exception('Mã OTP không đúng hoặc đã hết hạn.');
        }

        if ($otp->status === 'reset') {
            throw new \Exception('Đây là mã khôi phục mật khẩu.');
        }

        $user->email_verified_at = now();
        $user->save();

        $otp->delete();
        Session::forget('otp_user_email');
        Auth::loginUsingId($user->id);
    }
    public function verifyResetPasswordOtp(string $otpCode): void
{
    $email = Session::get('otp_user_email');
    $user = User::where('email', $email)->first();

    if (!$user) {
        throw new \Exception('Phiên xác minh không hợp lệ. Vui lòng thử lại từ đầu.');
    }

    $otp = Otp::where('email', $user->email)
        ->where('code', $otpCode)
        ->where('expires_at', '>', now())
        ->first();

    if (!$otp) {
        throw new \Exception('Mã OTP không đúng hoặc đã hết hạn.');
    }
    if ($otp->status !== 'reset') {
        throw new \Exception('Đây không phải là mã dùng để đặt lại mật khẩu.');
    }
    Session::put('reset_password_verified', true);

    $otp->delete();
}

    public function resendOtp(string $email, string $reset): void
    {
        $reset = 'reset';
        $this->sendOtp($email, $reset);
    }
}
