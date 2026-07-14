<?php

namespace App\Providers;

use App\Interfaces\Auth\AuthRepositoryInterface;
use App\Interfaces\User\UserRepositoryInterface;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\User\UserRepository;
use App\Interfaces\Dashboard\DashboardRepositoryInterface;
use App\Repositories\Dashboard\DashboardRepository;
use App\Interfaces\Category\CategoryRepositoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Interfaces\Brand\BrandRepositoryInterface;
use App\Repositories\Brand\BrandRepository;
use App\Interfaces\Supplier\SupplierRepositoryInterface;
use App\Repositories\Supplier\SupplierRepository;
use App\Interfaces\Tax\TaxRepositoryInterface;
use App\Repositories\Tax\TaxRepository;
use App\Interfaces\Uom\UomRepositoryInterface;
use App\Repositories\Uom\UomRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            AuthRepositoryInterface::class,
            AuthRepository::class
        );
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
        $this->app->bind(
            DashboardRepositoryInterface::class,
            DashboardRepository::class
        );
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );
        $this->app->bind(
            BrandRepositoryInterface::class,
            BrandRepository::class
        );
        $this->app->bind(
            SupplierRepositoryInterface::class,
            SupplierRepository::class
        );
        $this->app->bind(
            TaxRepositoryInterface::class,
            TaxRepository::class
        );
        $this->app->bind(
            UomRepositoryInterface::class,
            UomRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
