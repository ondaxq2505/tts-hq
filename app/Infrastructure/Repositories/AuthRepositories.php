<?php

namespace App\Infrastructure\Repositories;

use App\Core\Domain\Repositories\AuthRepositoryInterface;
use App\Core\Domain\Entities\UserEntity;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Core\Domain\Exceptions\BaseException;
use App\Core\Domain\DTO\BaseResponse;
use App\Core\Domain\UseCase\LoginUseCase;
use App\Infrastructure\Persistence\Models\Role;
use App\Library\Helpers;
use Carbon\Carbon;
use DB;
use Log;

class AuthRepositories implements AuthRepositoryInterface
{
    protected LoginUseCase $loginUseCase;
    public function __construct(LoginUseCase $loginUseCase)
    {
        $this->loginUseCase = $loginUseCase;
    }

    public function register(UserEntity $data): UserEntity
    {
        $result = User::create([
            'shop_id' => $data->shop_id,
            'username' => $data->username,
            'email' => $data->email,
            'account_type' => $data->account_type,
            'password' => Hash::make($data->password),
            'status' => 1,
            'balance' => 0,
        ]);
        return new UserEntity($result->toArray());
    }
    public function registerUser(UserEntity $data): UserEntity
    {
        $result = User::create([
            'shop_id' => $data->shop_id,
            'username' => $data->username,
            'email' => $data->email,
            'account_type' => 0,
            'password' => Hash::make($data->password),
            'status' => 1,
            'balance' => 0,
        ]);
      
        return new UserEntity($result->toArray());
    }

    public function login($username, $password)
    {
        $user = User::where('username', $username)
            ->where('status', 1)
            ->where(function ($query) {
                $query->orWhere('account_type', 1);
                $query->orWhere('account_type', 3);
            })
            ->first();
            if (!$user) {
                return BaseResponse::error("Tài khoản không tồn tại hoặc không hợp lệ", 401);
            }
        return $this->loginUseCase->handle($user, $password);
    }

    public function login_with_google(string $token): BaseResponse
    {
        try {
            $key_config = 'A2X4oYbBGkECUa0Eeo5AVAzZZh4Rwz43';
            $encrypt = 'zK25hWfe94i9QeRWtcyfROQFvEl4PO5G';
            $data = Helpers::Decrypt($token, $encrypt);
            if (empty($data)) {
                return BaseResponse::error("Thất bại", 403);
            }
            $data = explode('|', $data);
            if (empty($data)) {
                return BaseResponse::error("Thất bại", 403);
            }
            $status = $data[0];
            $key = $data[1];
            $time = $data[2];
            $email = $data[3];
            $provider_id = $data[4];
            if (empty($key)) {
                return BaseResponse::error("Thất bại", 403);
            }
            if ($key !== $key_config) {
                return BaseResponse::error("Thất bại", 403);
            }
            if (Carbon::now()->greaterThan(Carbon::createFromTimestamp($time))) {
                return BaseResponse::error("URL Hết hiệu lực", 404);
            }
            if ($status != 1) {
                return BaseResponse::error("Đăng nhập google không thành công.", 400);
            }
            if (!$email) {
                return BaseResponse::error("Không lấy được thông tin email khi đăng nhập với google.", 400);
            }
            $user = User::where('email', $email)
                ->where('status', 1)
                ->where(function ($query) {
                    $query->orWhere('account_type', 1);
                    $query->orWhere('account_type', 3);
                })
                ->first();
            if (!$user) {
                return BaseResponse::error("Tài khoản không tồn tại hoặc đã bị khóa.", 400);
            }
            if ($user->required_login_gmail != 1) {
                return BaseResponse::error("Tài khoản này không được phép login bằng google.", 400);
            }
            $user->update([
                'lastlogin_at' => Carbon::now()
            ]);

            return BaseResponse::success($user, 'Đăng nhập thành công', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return BaseResponse::error("Lỗi hệ thống, vui lòng thông báo QTV để xử lý ! " . $e->getMessage(), 500);
        }
    }
    public function resetPassword(array $data): array
    {
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );
    
        return [
            'success' => $status === Password::PASSWORD_RESET,
            'message' => __($status),
        ];
    }
}
