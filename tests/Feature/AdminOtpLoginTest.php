<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Carbon;
use App\Infrastructure\Persistence\Models\User;
use App\Notifications\SendOtpNotification;

class AdminOtpLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin()
    {
        return User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('SecurePass123@'),
            'account_type' => 1,
        ]);
    }
    /** @test */
    public function test_admin_login_with_invalid_or_expired_otp()
    {
        $admin = $this->createAdmin();

        Session::put('otp_user_id', $admin->id);
        Session::put('otp_code', '123456');
        Session::put('otp_expired_at', now()->subMinutes(10));

        $response = $this->post('/admin/otp/verify', [
            'otp' => '123456',
        ]);

        $response->assertSessionHasErrors(['otp']); 
    }
}
