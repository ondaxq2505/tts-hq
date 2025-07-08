<?php

namespace App\Core\Domain\UseCase;

use App\Core\Domain\DTO\BaseResponse;
use App\Core\Domain\Entities\UserEntity;
use App\Infrastructure\Persistence\Models\User;

class LoginUseCase
{
    public function handle(User $user, $password)
    {
        if (!$user) {
            return BaseResponse::error("Tài khoản hoặc mật khẩu không đúng", 401);
        }
        if ($user->required_login_gmail == 1) {
            return BaseResponse::error("Tài khoản của bạn đã được cấu hình đăng nhập với google. Vui lòng đăng nhập bằng tài khoản google để truy cập vào hệ thống", 400);
        }
        if (!\Hash::check($password, $user->password)) {
            return BaseResponse::error("Tài khoản hoặc mật khẩu không đúng", 401);
        }
        $result = new UserEntity($user->toArray());
        
        return BaseResponse::success($result, 'Đăng nhập thành công', 200);
    }
}
