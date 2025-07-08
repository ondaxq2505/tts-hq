<?php
 namespace App\Interfaces\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

 class CheckLockedUsersMiddaleware {

    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->status == 0) {
            Auth::logout();
            $loginRoute = $user->account_type == 1
                ? 'admin.login'
                : 'user.login';

            return redirect()->route($loginRoute)->withErrors([
                'account' => 'Tài khoản đã bị khóa. Vui lòng liên hệ quản trị viên.'
            ]);
        }
        return $next($request);
    }
 }