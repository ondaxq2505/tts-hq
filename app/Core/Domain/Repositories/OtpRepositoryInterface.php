<?php

namespace App\Core\Domain\Repositories;

use App\Infrastructure\Persistence\Models\User;

interface OtpRepositoryInterface
{
    public function sendOtp(string $email, string $status): void;
    public function sendResetLink(string $email): void;
    public function verifyOtp(string $otpCode): void;
    public function verifyResetPasswordOtp(string $otpCode): void;

    public function resendOtp(string $email, string $status): void;

}
