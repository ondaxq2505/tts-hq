<?php

namespace App\Interfaces\Http\Controllers\Admin\Auth;

use App\Core\Application\Services\OtpService;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Interfaces\Http\Requests\Auth\EmailRequest;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    protected OtpService $OtpService;
    public function __construct(
        OtpService $OtpService
    ) {
        $this->OtpService = $OtpService;
        $this->middleware('guest');
    }
    public function showLinkRequestForm()
    {
        return view('admin.auth.passwords.forgot-password');
    }

    public function sendResetLinkEmail(EmailRequest $request)
    {
        $email = $request->email;
    
        $status = Password::sendResetLink(['email' => $email]);
    
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link đặt lại mật khẩu đã được gửi tới email của bạn.');
        }       
        return back()->withErrors(['email' => __($status)]);
        
    }
}
