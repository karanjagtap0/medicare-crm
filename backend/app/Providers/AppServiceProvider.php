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
use App\Interfaces\Medicine\MedicineRepositoryInterface;
use App\Repositories\Medicine\MedicineRepository;
use App\Interfaces\Medicine\MedicineBatchRepositoryInterface;
use App\Repositories\Medicine\MedicineBatchRepository;
use App\Interfaces\Customer\CustomerRepositoryInterface;
use App\Repositories\Customer\CustomerRepository;
use App\Interfaces\Customer\CustomerAddressRepositoryInterface;
use App\Repositories\Customer\CustomerAddressRepository;
use App\Interfaces\Cart\CartRepositoryInterface;
use App\Repositories\Cart\CartRepository;
use App\Interfaces\Order\OrderRepositoryInterface;
use App\Repositories\Order\OrderRepository;
use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Repositories\Payment\PaymentRepository;
use App\Interfaces\Inventory\InventoryRepositoryInterface;
use App\Repositories\Inventory\InventoryRepository;
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
        $this->app->bind(
            MedicineRepositoryInterface::class,
            MedicineRepository::class
        );
        $this->app->bind(
            MedicineBatchRepositoryInterface::class,
            MedicineBatchRepository::class
        );
        $this->app->bind(
            CustomerRepositoryInterface::class,
            CustomerRepository::class
        );
        $this->app->bind(
            CustomerAddressRepositoryInterface::class,
            CustomerAddressRepository::class
        );
        $this->app->bind(
            CartRepositoryInterface::class,
            CartRepository::class
        );
        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );
        $this->app->bind(
            PaymentRepositoryInterface::class,
            PaymentRepository::class
        );
        $this->app->bind(
            InventoryRepositoryInterface::class,
            InventoryRepository::class
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
