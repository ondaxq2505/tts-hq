<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminDashboardTest extends TestCase
{
    //use RefreshDatabase;

    public function test_user_can_access_user_list_page_when_logged_in()
    {
        
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'account_type' => 1,
        ]);

        $response = $this->actingAs($user)
                         ->get('/admin/dashboard');


        $response->assertStatus(200);
    }
}
