<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserListTest extends TestCase
{
    //use RefreshDatabase;

    public function test_admin_can_view_user_list_page()
    {
        $admin = User::factory()->create([
            'username' => 'adminuser',
            'password' => Hash::make('adminpassword'),
            'account_type' => 1,
        ]);

        $user1 = User::factory()->create([
            'fullname' => 'Nguyen Van A',
            'username' => 'nguyenvana',
            'email' => 'vana@example.com',
            'account_type' => 0,
            'status' => 1,
        ]);

        $user2 = User::factory()->create([
            'fullname' => 'Tran Thi B',
            'username' => 'tranthib',
            'email' => 'thib@example.com',
            'account_type' => 0,
            'status' => 0,
        ]);

        $response = $this->actingAs($admin)
                         ->get('/admin/users');

 
        $response->assertStatus(200);
        
        $response->assertSee('Danh sách người dùng');
        $response->assertSee('Nguyen Van A');
        $response->assertSee('nguyenvana');
        $response->assertSee('vana@example.com');
        $response->assertSee('Hoạt động');

        $response->assertSee('Tran Thi B');
        $response->assertSee('tranthib');
        $response->assertSee('thib@example.com');
        $response->assertSee('Đã khóa'); 
    }
}
