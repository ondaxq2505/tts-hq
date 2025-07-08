<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use App\Infrastructure\Persistence\Models\User;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_password_reset_link()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'ondaxq2505@gmail.com',
        ]);

        $response = $this->post('/forgot-password', [
            'email' => 'ondaxq2505@gmail.com',
        ]);

        $response->assertStatus(302);
        Notification::assertSentTo($user, \Illuminate\Auth\Notifications\ResetPassword::class);
    }

    public function test_password_confirmation_does_not_match()
    {
        $token = Password::createToken($user = User::factory()->create());

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'Nham234@',
            'password_confirmation' => 'Nham323@',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_password_does_not_meet_requirements()
    {
        $token = Password::createToken($user = User::factory()->create());

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => '1111111',
            'password_confirmation' => '1111111',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_reset_password_link_expired()
    {
        $user = User::factory()->create();

        $token = Password::broker()->createToken($user);

        $this->travel(2)->hours();

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'Valid123@',
            'password_confirmation' => 'Valid123@',
        ]);

        $response->assertSessionHasErrors('email'); 
    }
}
