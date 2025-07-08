<?php

namespace App\Infrastructure\Repositories;

use App\Core\Domain\Repositories\UserRepositoryInterfacee;
use App\Core\Domain\Entities\UserEntity;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;


class UserRepositories implements UserRepositoryInterfacee
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }
    public function searchPaginate(?string $keyword = null, int $perPage = 10): LengthAwarePaginator
    {
    $query = User::query();

    if (!empty($keyword)) {
        $query->where(function ($q) use ($keyword) {
            $q->where('fullname', 'like', "%$keyword%")
              ->orWhere('username', 'like', "%$keyword%")
              ->orWhere('email', 'like', "%$keyword%");
        });
    }
    return $query->paginate($perPage);
    }

    public function findById(int $id): ?UserEntity
    {
        $user = User::find($id);
        return $user ? new UserEntity($user->toArray()) : null;
    }

    public function update(int $id, array $data): bool
    {
        return User::where('id', $id)->update($data);
    }
   
}
