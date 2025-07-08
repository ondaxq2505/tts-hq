<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Support\Facades\Hash;

class UserHomeTest extends TestCase
{
  
    public function test_user_can_access_user_list_page_when_logged_in()
    {
        // Tạo 1 user giả lập
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123'), // mã hóa mật khẩu
        ]);

        // Giả lập đăng nhập
        $response = $this->actingAs($user)
                         ->get('/user/home');

        // Kiểm tra status trả về 200 (OK)
        $response->assertStatus(200);
    }
}
