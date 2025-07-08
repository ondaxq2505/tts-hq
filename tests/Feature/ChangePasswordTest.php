<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    protected $user;

    // Hàm này sẽ được gọi trong tất cả các test để tạo user
    protected function setUp(): void
    {
        parent::setUp();

        // Tạo user giả lập với mật khẩu mặc định
        $this->user = User::factory()->create([
            'username' => 'ondaxq',
            'password' => Hash::make('Ondaxq2505+'),
        ]);
    }

    /** @test */
    public function test_user_can_visit_change_password_form_and_change_password()
    {
        $this->actingAs($this->user);

        $this->get('/change-password');

        $response = $this->post('/change-password', [
            'old_password'          => 'Ondaxq2505+',
            'password'              => 'NewPass123@',
            'password_confirmation' => 'NewPass123@',
        ]);

        $response->assertRedirect('/change-password');
        $response->assertSessionHas('success', 'Đổi mật khẩu thành công');

        $this->assertTrue(Hash::check('NewPass123@', $this->user->fresh()->password));
    }

    /** @test */
    public function test_current_password_incorrect()
    {
        $this->actingAs($this->user);

        $response = $this->post('/change-password', [
            'old_password'          => 'WrongPass123!',
            'password'              => 'NewPass123@',
            'password_confirmation' => 'NewPass123@',
        ]);
        $response->assertSessionHasErrors(['old_password' => 'Mật khẩu cũ không đúng']);
    }

    /** @test */
    public function test_new_password_not_meet_requirements()
    {
        $this->actingAs($this->user);

        $response = $this->post('/change-password', [
            'old_password'          => 'Ondaxq2505+',
            'password'              => 'short',
            'password_confirmation' => 'short',
        ]);
        $response->assertSessionHasErrors([
            'password' => 'Mật khẩu mới phải có ít nhất 8 ký tự, bao gồm cả chữ hoa, chữ thường, số, ký tự đặc biệt.'
        ]);
    }

    /** @test */
    public function test_confirmation_mismatch()
    {
        $this->actingAs($this->user);
        $response = $this->post('/change-password', [
            'old_password'          => 'Ondaxq2505+',
            'password'              => 'ValidNew1@',
            'password_confirmation' => 'DifferentNew1@',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'Mật khẩu xác nhận không đúng.'
        ]);
    }

    /** @test */
    public function test_new_password_same_as_old()
    {
        $this->actingAs($this->user);

       
        $response = $this->post('/change-password', [
            'old_password'          => 'Ondaxq2505+',
            'password'              => 'Ondaxq2505+',
            'password_confirmation' => 'Ondaxq2505+',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'Mật khẩu mới trùng với mật khẩu cũ. Vui lòng nhập lại'
        ]);
    }
}
