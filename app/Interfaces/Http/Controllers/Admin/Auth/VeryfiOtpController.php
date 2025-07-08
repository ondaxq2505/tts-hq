<?php

namespace App\Interfaces\Http\Controllers\Admin\Auth;

use App\Interfaces\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Auth\VerifyOtpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Core\Application\Services\OtpService;
use Illuminate\Support\Facades\Session;

class VeryfiOtpController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showOtpForm()
    {
        
        $user = Auth::user();
        $email = $user ? $user->email : session('otp_user_email');
        if (!$email) {
            return redirect()->route('admin.login')->withErrors([
                'otp' => 'Phiên không hợp lệ. Vui lòng đăng nhập lại.'
            ]);
        }
        if (!session()->has('otp_sent')) {
            $this->otpService->sendOtp($email, 'verifi');
            session([
                'otp_user_email' => $email,
                'otp_sent' => true
            ]);
        }
        return view('admin.auth.otp');
        }
        public function showOtpFormResetPassword()
        {
            $user = Auth::user();
            $email = $user ? $user->email : session('otp_user_email');
    
            if (!$email) {
                return redirect()->route('admin.login')->withErrors([
                    'otp' => 'Phiên không hợp lệ. Vui lòng đăng nhập lại.'
                ]);
            }
            if (!session()->has('otp_sent')) {
                $this->otpService->sendOtp($email, 'reset');
                session([
                    'otp_user_email' => $email,
                    'otp_sent' => true
                ]);
            }
            return view('admin.auth.otpresetpassword');
            }
    
    public function verifyOtp(VerifyOtpRequest $request)
    {
        try {
            $this->otpService->verifyOtp($request->otp);
            Session::put('otp_verified', true);
            
            return redirect()->route('admin.users.index')->with('success', 'Xác thực thành công');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['otp' => $e->getMessage()]);
        }
    }
    public function verifyOtpResetPassword(VerifyOtpRequest $request)
    {
        try {
            $this->otpService->verifyResetPasswordOtp($request->otp);
            Session::put('otp_verified', true);
    
            return redirect()->route('admin.password.reset')->with('success', 'Xác thực thành công');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['otp' => $e->getMessage()]);
        }
    }

    public function resendOtp(Request $request)
    {
        $email = $request->input('email') ?? session('otp_user_email');;
        $user = Auth::user();
        $status = 'verifi';
        if (!$user && !$email) {
            return redirect()->route('admin.login')->withErrors([
                'otp' => 'Tài khoản không hợp lệ hoặc đã xác thực.'
            ]);
        }
        if (!$user) {
        $this->otpService->sendOtp($email, $status);
        }
        if (!$email) {
        $this->otpService->sendOtp($user->email,$status);
        }
        dd($email);
        //return back()->with('success', 'Mã OTP mới đã được gửi lại!');
    }
    public function resendOtpResetPassword(Request $request)
    {

        $user = Auth::user();
        $email = session('otp_user_email');
        $status = 'reset';
        if (!$user && !$email) {
            return redirect()->route('admin.login')->withErrors([
                'otp' => 'Tài khoản không hợp lệ hoặc đã xác thực.'
            ]);
        }
        if (!$user) {
        $this->otpService->resendOtp($email, $status);
        }
        if (!$email) {
        $this->otpService->resendOtp($user->email,$status);
        }

        return back()->with('success', 'Mã OTP mới đã được gửi lại!');
    }
}
