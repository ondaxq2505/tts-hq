<?php

namespace App\Core\Application\Services;
use App\Core\Domain\Repositories\UserRepositoryInterfacee;
use App\Core\Domain\Entities\UserEntity;
use App\Core\Domain\DTO\BaseResponse;
use App\Core\Domain\Repositories\ImageStorageInterface;
use Illuminate\Http\UploadedFile;


class UserService {
    protected UserRepositoryInterfacee $userRepository;
    protected ImageStorageInterface $imageStorage;

    public function __construct(UserRepositoryInterfacee $userRepository, ImageStorageInterface $imageStorage)
    {
        $this->userRepository = $userRepository;
        $this->imageStorage = $imageStorage;
    }
    public function getPaginatedUsers($perPage = 10)
    {
        return $this->userRepository->paginate($perPage);
    }
    public function searchPaginate(?string $keyword = null, int $perPage = 10){
        return $this->userRepository->searchPaginate($keyword,$perPage);
    }
    public function getUserById(int $id)
    {
        return $this->userRepository->findById($id);
    }

    public function updateUser(int $id, array $data): bool
    {
        return $this->userRepository->update($id, $data);
    }
    public function updateAvatar(int $id, UploadedFile $file): bool
    {
        $avatarPath = $this->imageStorage->store($file, 'avatars/');

        return $this->userRepository->update($id, ['avatar' => $avatarPath]);
    }

}