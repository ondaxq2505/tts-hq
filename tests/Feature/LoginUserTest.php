<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login_form()
     {
        $response = $this->get('/login');
        $response->assertStatus(200);
     }
    /** @test */
    public function test_login()
    {
        $user = User::factory()->create([
            'username' => 'ondaxq',
            'password' => Hash::make('Ondaxq2505+'),
        ]);

        $response = $this
        ->withSession(['_token' => csrf_token()])
        ->post('/login', [
            'username' => 'ondaxq',
            'password' => 'Ondaxq2505+',
        ]);
    
        $response->assertRedirect(route('user.home'));
    }
    /** @test */
    public function test_login_fail_wrong_password()
    {
        User::factory()->create([
            'username' => 'ondaxq',
            'password' => Hash::make('Ondaxq2505+'),
        ]);

        $response = $this->post('/login', [
            'username' => 'ondaxq',
            'password' => 'Wrong2-password',
        ]);

        $response->assertRedirect(); 
        $response->assertSessionHasErrors('username');
    }

    /** @test */
    public function test_login_fail_username_not_exist()
    {
        $response = $this->post('/login', [
            'username' => 'doesnotexist',
            'password' => 'Any-password1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('username');
    }
    /** @test */
public function test_login_blocked_temporarily_after_5_attempts()
{
    $user = User::factory()->create([
        'username' => 'ondaxq',
        'password' => Hash::make('Ondaxq2505+'),
    ]);

    for ($i = 0; $i < 5; $i++) {
        $this->post('/login', [
            'username' => 'ondaxq',
            'password' => 'WrongPass123!',
        ]);
    }

    $response = $this->post('/login', [
        'username' => 'ondaxq',
        'password' => 'Ondaxq2505+',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('errors');
    $this->assertStringContainsString(
        'Vui lòng thử lại sau',
        session('errors')->first('username')
    );
}

/** @test */
    public function test_login_redirects_to_forgot_password_after_8_failed_attempts()
    {
            $user = User::factory()->create([
                'username' => 'ondaxq',
                'password' => Hash::make('Ondaxq2505+'),
            ]);
    
            $username = 'ondaxq';
            $throttleKey = Str::lower($username);
    
            for ($i = 0; $i < 5; $i++) {
                $this->post('/login', [
                    'username' => $username,
                    'password' => 'WrongPass123!',
                ]);
            }
            $response = $this->post('/login', [
                'username' => $username,
                'password' => 'WrongPass123!',
            ]);
            $response->assertRedirect();
            $response->assertSessionHas('errors');
            $this->assertStringContainsString(
                'Vui lòng thử lại sau',
                session('errors')->first('username')
            );
            RateLimiter::clear($throttleKey);
            $this->travel(61)->seconds();
    
            for ($i = 0; $i < 3; $i++) {
                $response = $this->post('/login', [
                    'username' => $username,
                    'password' => 'WrongPass123!',
                ]);
            }
            $response->assertRedirect();
            $response->assertSessionHas('errors');
            $this->assertStringContainsString(
                'Bạn còn 0 lần thử trước khi bị khóa vĩnh viễn',
                session('errors')->first('username')
            );
            $response = $this->post('/login', [
                'username' => $username,
                'password' => 'Ondaxq2505+',
            ]);
            $response->assertRedirect('/forgot-password');
            $this->assertStringContainsString(
                'Vui lòng đặt lại mật khẩu để tiếp tục',
                session('errors')->first('username')
            );
        }
        public function test_returns_error_when_email_not_found_in_system()
    {
        
        $response = $this->post('/login', [
            'username' => 'nonexistentuser',
            'password' => 'SomeRandomPassword1!',
        ]);

        $response->assertSessionHasErrors('username');
    }
}
