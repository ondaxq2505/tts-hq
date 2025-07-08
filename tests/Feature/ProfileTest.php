<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_view_their_profile_information()
    {
        // Tạo user giả lập
        $user = User::factory()->create([
            'fullname' => 'NGUYEN XUAN QUYEN',
            'username' => 'nxquyen',
            'email' => 'nxquyen.dhti15a13hn@sv.uneti.edu.vn',
            'account_type' => 0, 
            'status' => 1, 
            'password' => Hash::make('password123'),
        ]);
        $response = $this->actingAs($user)
                         ->get('/profile');
        $response->assertStatus(200);

        $response->assertSee('Avatar');
        $response->assertSee('ID:');
        $response->assertSee('Họ và tên:');
        $response->assertSee('NGUYEN XUAN QUYEN');
        $response->assertSee('Tên đăng nhập:');
        $response->assertSee('nxquyen');
        $response->assertSee('Email:');
        $response->assertSee('nxquyen.dhti15a13hn@sv.uneti.edu.vn');
        $response->assertSee('Quyền:');
        $response->assertSee('Người dùng');
        $response->assertSee('Trạng thái:');
        $response->assertSee('Hoạt động');
    }
}
