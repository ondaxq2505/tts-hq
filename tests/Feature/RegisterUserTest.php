<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Middleware\VerifyCsrfToken;
use App\Infrastructure\Persistence\Models\User;
class RegisterUserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_shows_create_user_form()
     {
        $response = $this->get('/register');
        $response->assertStatus(200);
     }
    /** @test */
    public function test_creates_user_and_redirects()
    {
        $response = $this
            ->withoutMiddleware([VerifyCsrfToken::class])
            ->post('/register', [
                'username' => 'truongdev1',
                'email' => 'ondaxq2505@gmail.com',
                'password' => 'Ondaxq2505+',
                'password_confirmation' => 'Ondaxq2505+',
            ]);
        $response->assertRedirect(route('user.otp.verify.form'));
    }
    public function test_rejects_duplicate_email()
    {
        
        $existingUser = User::factory()->create([
            'email' => 'existinguser@example.com',
        ]);
    
        // Gửi yêu cầu đăng ký với email đã tồn tại
        $response = $this->post('/register', [
            'username' => 'newuser',
            'email' => 'existinguser@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);
    
        // Kiểm tra rằng có lỗi về email trùng lặp
        $response->assertSessionHasErrors('email');
    }
    public function test_rejects_invalid_email_format()
    {
        $response = $this->post('/register', [
            'username' => 'user',
            'email' => 'dangnham.gmail.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertSessionHasErrors('email');
    }
    /** @test */
    public function test_requires_email_field()
    {
        $response = $this->post('/register', [
            'username' => 'user',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_cannot_register_without_required_fields()
    {

        $response = $this->post('/register', [
            'email' => 'truongdev1@gmail.com',
            'password' => 'Ondaxq2505+',
            'password_confirmation' => 'Ondaxq2505+',
        ]);
        $response->assertSessionHasErrors('username'); 

        $response = $this->post('/register', [
            'username' => 'truongdev1',
            'password' => 'Ondaxq2505+',
            'password_confirmation' => 'Ondaxq2505+',
        ]);
        $response->assertSessionHasErrors('email'); 

        $response = $this->post('/register', [
            'username' => 'truongdev1',
            'email' => 'truongdev1@gmail.com',
            'password_confirmation' => 'Ondaxq2505+',
        ]);
        $response->assertSessionHasErrors('password'); 

        $response = $this->post('/register', [
            'username' => 'truongdev1',
            'email' => 'truongdev1@gmail.com',
            'password' => 'Ondaxq2505+',
        ]);
        $response->assertSessionHasErrors('password'); 
    }
    /** @test */
    public function test_rejects_password_that_does_not_meet_complexity_requirements()
    {
        $response = $this->post('/register', [
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => '11111111',
            'password_confirmation' => '11111111',
        ]);

        $response->assertSessionHasErrors('password');
    }

}
