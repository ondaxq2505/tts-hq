<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Support\Facades\Hash;

class LockedUserLoginTest extends TestCase
{
    //use RefreshDatabase;

    public function test_locked_user_cannot_login()
    {
        $user = User::factory()->create([
            'username' => 'lockeduser',
            'email' => 'locked@example.com',
            'account_type' => 0,
            'password' => Hash::make('Password123+'),
            'status' => 0, 
        ]);

        $response = $this->post('/login', [
            'username' => 'lockeduser',
            'password' => 'Password123+',
        ]);
        $response->assertRedirect('/user/home');
        $homeResponse = $this->actingAs($user)->get('/user/home');
        $homeResponse->assertRedirect('/login');
    }
}
