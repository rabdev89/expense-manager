<?php

namespace App\Providers;

use App\Components\Categories\Repositories\CategoryRepository;
use App\Components\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Components\Permissions\Repositories\PermissionRepository;
use App\Components\Permissions\Repositories\PermissionRepositoryInterface;
use App\Components\Roles\Repositories\RoleRepository;
use App\Components\Roles\Repositories\RoleRepositoryInterface;
use App\Components\Expenses\Repositories\ExpensesRepository;
use App\Components\Expenses\Repositories\Interfaces\ExpensesRepositoryInterface;
use App\Components\Users\Repositories\UsersRepository;
use App\Components\Users\Repositories\UsersRepositoryInterface;
use App\Components\Base\Repositories\BaseRepository;
use App\Components\Base\Repositories\BaseRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {

        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            ExpensesRepositoryInterface::class,
            ExpensesRepository::class
        );

        $this->app->bind(
            RoleRepositoryInterface::class,
            RoleRepository::class
        );

        $this->app->bind(
            PermissionRepositoryInterface::class,
            PermissionRepository::class
        );

        $this->app->bind(
            UsersRepositoryInterface::class,
            UsersRepository::class
        );

        $this->app->bind(
            BaseRepositoryInterface::class,
            BaseRepository::class
        );
    }
}
