<?php
 namespace App\Interfaces\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

 class CheckEmailVerifielMiddaleware {

    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if(is_null( $user->email_verified_at)){
            if ($user->account_type === 0) {
                return redirect()->route('user.otp.verify.form');
            }
            if ($user->account_type === 1) {
                return redirect()->route('admin.otp.verify.form');
            }
        }
        return $next($request);
    }
 }