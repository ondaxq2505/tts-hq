<?php

namespace App\Core\Domain\Repositories;

use App\Core\Domain\Entities\UserEntity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

interface UserRepositoryInterfacee
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function searchPaginate(?string $keyword = null, int $perPage = 10): LengthAwarePaginator;
    public function findById(int $id): ?UserEntity;
    public function update(int $id, array $data): bool;
}