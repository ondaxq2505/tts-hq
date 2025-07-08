<?php

namespace App\Interfaces\Http\Controllers\User\Auth;

use App\Interfaces\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Interfaces\Http\Requests\Auth\LoginRequest;
use App\Core\Domain\Entities\UserEntity;
use App\Core\Application\Services\AuthService;
use App\Core\Application\Services\ActivityLogService;
use App\Core\Application\Services\OtpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Library\Helpers;
use Illuminate\Support\Facades\Session;
use DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected AuthService $authService;

    protected $redirectAfterLogout ="";

    protected $maxAttempts=5;

    protected $decayMinutes=1;

    
    protected ActivityLogService $activity_log_service;
    protected OtpService $OtpService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
            AuthService $authService,
            ActivityLogService $activity_log_service,
            OtpService $OtpService
        )
    {
        $this->middleware('guest')->except('logout');
        $this->authService = $authService;
        $this->OtpService = $OtpService;
        $this->redirectTo = route('user.home');
        $this->redirectAfterLogout = route('user.login');
        $this->activity_log_service = $activity_log_service;
    }

    public function showLoginForm()
    {
        return view('user.auth.login');
    }
    public function login(LoginRequest $request)
    {
        $username     = $request->input($this->username());
        $throttleKey  = $this->throttleKey($request);
        $customKey    = 'login_attempts:' . Str::lower($username);

        $maxAttempts      = 5;
        $superMaxAttempts = 8;

        $totalAttempts = Cache::get($customKey, 0);

        if ($totalAttempts >= $superMaxAttempts) {
            return redirect()->route('user.password.request')
                         ->withErrors(['username' => 'Bạn đã nhập sai quá nhiều lần. Vui lòng đặt lại mật khẩu để tiếp tục.']);
        }

        if ($this->limiter()->tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = $this->limiter()->availableIn($throttleKey);
            return back()->withErrors([
                'username' => "Bạn đã nhập sai quá nhiều lần. Vui lòng thử lại sau {$seconds} giây.",
            ]);
        }
        if ($this->attemptLogin($request)) {
            $this->clearLoginAttempts($request);
            Cache::forget($customKey);

            $this->activity_log_service->add('Đăng nhập thành công');
            return $this->sendLoginResponse($request);
        }
        $this->incrementLoginAttempts($request);
        Cache::put($customKey, $totalAttempts + 1, now()->addDays(1));

        $remaining = $superMaxAttempts - ($totalAttempts + 1);

        return back()->withErrors([
            'username' => "Sai thông tin đăng nhập. Bạn còn $remaining lần thử trước khi bị khóa vĩnh viễn.",
        ]);
    }


    public function logout(Request $request){

        $this->activity_log_service->add('Đăng xuất tài khoản ADMIN thành công');

        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()? new JsonResponse([], 204) : redirect($this->redirectAfterLogout);
    }
   
    public function username()
    {
        return 'username'; 
    }

}
