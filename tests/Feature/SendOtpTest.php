<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Infrastructure\Repositories\OtpRepository;
use App\Infrastructure\Persistence\Models\Otp;
use App\Mail\SendOtp;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendOtpTest extends TestCase
{
    //use RefreshDatabase;

    /** @test */
    public function it_sends_and_stores_otp_correctly()
    {
        Mail::fake(); 

        $repository = new OtpRepository();
        $email = 'test@example.com';
        $status = 'login';

        $repository->sendOtp($email, $status);

        $this->assertDatabaseHas('otps', [
            'email' => $email,
            'status' => $status,
        ]);

        $otp = Otp::where('email', $email)->first();

        $this->assertNotNull($otp->code);
        $this->assertTrue(now()->lt($otp->expires_at)); 

        Mail::assertSent(SendOtp::class, function ($mail) use ($email, $otp) {
            return $mail->hasTo($email) && $mail->otp === $otp->code;
        });
    }
}
