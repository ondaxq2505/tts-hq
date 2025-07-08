<?php

namespace App\Interfaces\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdminLoginMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user->account_type == 1) {
            Auth::logout();
            return redirect()->route('admin.login');
        }
        return $next($request);
    }
}
