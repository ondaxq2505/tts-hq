<?php

namespace App\Core\Application\Services;

use App\Core\Domain\Repositories\OtpRepositoryInterface;
use App\Core\Domain\Repositories\AuthRepositoryInterface;

class OtpService
{
    protected OtpRepositoryInterface $otpRepository;
    protected AuthRepositoryInterface $authRepository;

    public function __construct(
        OtpRepositoryInterface $otpRepository,
        AuthRepositoryInterface $authRepository
    ) {
        $this->otpRepository = $otpRepository;
        $this->authRepository =  $authRepository;
    }

    public function sendOtp(string $email, string $status): void
    {
        $this->otpRepository->sendOtp($email, $status);
    }
    public function sendResetLink(string $email): void
    {
        $this->otpRepository->sendResetLink($email);
    }
    public function verifyOtp(string $otpCode): void
    {
        $this->otpRepository->verifyOtp($otpCode);
    }
    public function verifyResetPasswordOtp(string $otpCode): void
    {
        $this->otpRepository->verifyResetPasswordOtp($otpCode);
    }

    public function resendOtp(string $email, string $status): void
    {
        $this->otpRepository->resendOtp($email, $status);
    }
}
