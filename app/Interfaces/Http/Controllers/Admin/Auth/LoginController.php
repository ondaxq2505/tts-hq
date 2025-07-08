<?php

namespace App\Interfaces\Http\Controllers\Admin\Auth;

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
        $this->redirectTo = route('admin.users.index');
        $this->redirectAfterLogout = route('admin.login');
        $this->activity_log_service = $activity_log_service;
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }
    public function login(LoginRequest $request)
    {
        $username = $request->input($this->username());
        $ip = $request->ip();
        $throttleKey = $this->throttleKey($request); 
        $customKey = 'login_attempts:' . $username . ':' . $ip; 

        $maxAttempts = 5;
        $decayMinutes = 1;
        $superMaxAttempts = 8;

        $totalAttempts = Cache::get($customKey, 0);

        if ($totalAttempts >= $superMaxAttempts) {
            return redirect()->route('admin.password.request')->withErrors([
                'username' => 'Bạn đã nhập sai quá nhiều lần. Vui lòng đặt lại mật khẩu để tiếp tục.',
            ]);
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

            $user = Auth::user();
            if ($user->account_type === 1) {
                Auth::logout();
                $this->OtpService->sendOtp($user->email, 'verifi');

                session([
                    'otp_user_email' => $user->email,
                    'otp_sent' => true,
                    'pending_admin_login' => true,
                ]);

                return redirect()->route('admin.otp.verify.form')
                                ->with('success', 'Mã OTP đã được gửi đến email. Vui lòng xác minh để đăng nhập.');
            }

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
    public function loginGmail(Request $request){
        $url = config('services.google.return_url');
        if(empty($url)){
            return redirect()->to(route('admin.login'))->with('error_login_gmail', 'Google OAuth 2.0 chưa được cấu hình.Vui lòng liên hệ QTV để kịp thời xử lý');
        }
        $url = $url.'/'.str_replace(".","_",$request->getHost());
        return redirect()->to($url);
    }
    public function callbackLoginGmail(Request $request,$token){
        if(!$token){
            abort(403);
        }
        $result = $this->authService->login_with_google($token);

        if ($result->success === false) {
            if($result->code == 403 ){
                abort(403);
            }
            if($result->code == 404){
                abort(404);
            }
            return redirect()->to(route('admin.login'))->with('error_login_gmail',$result->message);
        }

        Auth::loginUsingId($result->data->id);

        $this->activity_log_service->add('Đăng nhập ADMIN thành công');

        return redirect()->intended($this->redirectPath());
    }
    public function username()
    {
        return 'username'; 
    }

}
