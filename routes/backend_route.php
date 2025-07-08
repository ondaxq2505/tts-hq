<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\Admin\UserController;
use App\Interfaces\Http\Controllers\Admin\Auth\RegisterController;
use App\Interfaces\Http\Controllers\Admin\Auth\LoginController;
use App\Interfaces\Http\Controllers\Admin\Auth\VeryfiOtpController;
use App\Interfaces\Http\Controllers\Admin\User\ProfileController;
use App\Interfaces\Http\Controllers\Admin\PermissionController;
use App\Interfaces\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Interfaces\Http\Controllers\Admin\Auth\ResetPasswordController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(array('as' => 'admin.'), function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('login/gmail', [LoginController::class, 'loginGmail'])->name('login.gmail');
    Route::get('callback-login/gmail/{token}', [LoginController::class, 'callbackLoginGmail']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('otp/resend', [VeryfiOtpController::class,  'resendOtp'])->name('otp.resend');
    Route::post('otp/resend_reset_password', [VeryfiOtpController::class, 'resendOtpResetPassword'])->name('otp.resend_reset_password');
    Route::get('/otp/verify', [VeryfiOtpController::class, 'showOtpForm'])->name('otp.verify.form');
    Route::get('/otp/verifyresetpassword', [VeryfiOtpController::class, 'showOtpFormResetPassword'])->name('otp.verifyresetpassword.form');
    Route::post('/otp/verify', [VeryfiOtpController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/otp/verifyresetpassword', [VeryfiOtpController::class, 'verifyOtpResetPassword'])->name('otp.verifyresetpassword');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    //Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


    Route::middleware(['auth','check.locked','check.admin','check.email_verified'])->group(function () {
        // Route::get('/security-2fa/very',[ProfileController::class, 'getVery'])->name('security-2fa.very');
        // Route::post('/security-2fa/very',[ProfileController::class, 'postVery']);
        Route::middleware('2fa')->group(function () {
            Route::get('/dashboard', function () {
                $page_title = 'Dashboard';
                $page_breadcrumbs = [
                    [
                        'page' => route('admin.dashboard'),
                        'title' => 'Home',
                    ],
                ];
                return view('admin.index', compact('page_title', 'page_breadcrumbs'));
            })->name('dashboard');
            Route::get('/', function () {
                $page_title = 'Dashboard';
                $page_breadcrumbs = [
                    [
                        'page' => route('admin.dashboard'),
                        'title' => 'Home',
                    ],
                ];
                return view('admin.index', compact('page_title', 'page_breadcrumbs'));
            });
            Route::get('/profile', [ProfileController::class, 'getProfile'])->name('profile');
            Route::post('/change-password', [ProfileController::class, 'postChangePassword'])->name('change-password');
            Route::post('/change-password2', [ProfileController::class, 'postChangePassword2'])->name('change-password2');
            Route::get('/security-2fa', [ProfileController::class, 'get_security_2fa'])->name('security-2fa.index');
            Route::get('/security-2fa/setup', [ProfileController::class, 'setup_google_2fa'])->name('security-2fa.setup');
            Route::post('/security-2fa/setup', [ProfileController::class, 'enable2fa']);
            Route::post('/security-2fa/disable2fa', [ProfileController::class, 'disable2fa'])->name('security-2fa.disable2fa');
            Route::get('/security-2fa/recovery-code', [ProfileController::class, 'getRecoveryCode'])->name('security-2fa.recovery-code');
            // Route::post('role/order', 'Admin\RoleController@order')->name('role.order');
            // Route::resource('role','Admin\RoleController');
            Route::post('permission/order', 'Admin\PermissionController@order')->name('permission.order');
            Route::resource('permission', 'Admin\PermissionController');
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/{id}/show', [UserController::class, 'show'])->name('users.show');
            Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
            Route::put('/users/{id}/status', [UserController::class, 'updateStatus'])->name('users.updatestatus');
            Route::put('/users/{id}/role', [UserController::class, 'updateRole'])->name('users.update_role');
        });
    });
});

Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
 Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');