<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Core\Domain\Repositories\UserRepositoryInterfacee;
use App\Core\Domain\Repositories\AuthRepositoryInterface;
use App\Core\Domain\Repositories\ProfileRepositoryInterface;
use App\Core\Domain\Repositories\ActivityLogRepositoryInterface;
use App\Core\Domain\Repositories\OtpRepositoryInterface;
use App\Core\Domain\Repositories\PermissionRepositoryInterface;
use App\Core\Domain\Repositories\ImageStorageInterface;
use App\Infrastructure\Repositories\ActivityLogRespositories;
use App\Infrastructure\Repositories\AuthRepositories;
use App\Infrastructure\Repositories\OtpRepository;
use App\Infrastructure\Repositories\ProfileRepositories;
use App\Infrastructure\Repositories\PermissionRepositories;
use App\Infrastructure\Repositories\UserRepositories;
use App\Infrastructure\Repositories\LocalImageStorageRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepositories::class);
        $this->app->bind(ProfileRepositoryInterface::class, ProfileRepositories::class);
        $this->app->bind(ActivityLogRepositoryInterface::class, ActivityLogRespositories::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepositories::class);
        $this->app->bind(OtpRepositoryInterface::class, OtpRepository::class);
        $this->app->bind(UserRepositoryInterfacee::class, UserRepositories::class);
        $this->app->bind(ImageStorageInterface::class, LocalImageStorageRepository::class);
    }

    public function boot()
    {
        //
    }
}
