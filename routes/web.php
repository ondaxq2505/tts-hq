<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\User\Auth\RegisterController;
use App\Interfaces\Http\Controllers\User\Auth\VeryfiOtpController;
use App\Interfaces\Http\Controllers\User\Auth\LoginController;
use App\Interfaces\Http\Controllers\User\Auth\ForgotPasswordController;
use App\Interfaces\Http\Controllers\User\Auth\ResetPasswordController;
use App\Interfaces\Http\Controllers\User\User\ProfileController;
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
Route::get('/', function () {
    return view('welcome');
});
Route::group(['as' => 'user.'], function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login');

    Route::get('login/gmail', [LoginController::class, 'loginGmail'])->name('login.gmail');
    Route::get('callback-login/gmail/{token}', [LoginController::class, 'callbackLoginGmail']);

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');

    // OTP
    Route::post('otp/resend', [VeryfiOtpController::class, 'resendOtp'])->name('otp.resend');
    Route::post('otp/resend_reset_password', [VeryfiOtpController::class, 'resendOtpResetPassword'])->name('otp.resend_reset_password');
    Route::get('/otp/verify', [VeryfiOtpController::class, 'showOtpForm'])->name('otp.verify.form');
    Route::post('/otp/verify', [VeryfiOtpController::class, 'verifyOtp'])->name('otp.verify');
    Route::get('/otp/verifyresetpassword', [VeryfiOtpController::class, 'showOtpFormResetPassword'])->name('otp.verifyresetpassword.form');
    Route::post('/otp/verifyresetpassword', [VeryfiOtpController::class, 'verifyOtpResetPassword'])->name('otp.verifyresetpassword');

    // Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
    Route::get('/change-password', [ProfileController::class, 'showChangePassword'])->name('change-password');
    Route::post('/change-password', [ProfileController::class, 'postChangePassword'])->name('change-password');

    Route::get('/profile', [ProfileController::class, 'getProfile'])->name('profile');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/edit/{id}', [ProfileController::class, 'update'])->name('update');
    Route::post('/editAvatar/{id}', [ProfileController::class, 'updateAvatar'])->name('updateAvatar');
});

Route::middleware(['auth','check.admin.login', 'check.locked', 'check.email_verified'])->prefix('user')->name('user.')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/home', function () {
        $page_title = 'Home';
        $page_breadcrumbs = [
            [
                'page' => route('user.home'),
                'title' => 'Home',
            ],
        ];
        $user = auth()->user(); 
        return view('user.index', compact('page_title', 'page_breadcrumbs','user'));
    })->name('home');
});
