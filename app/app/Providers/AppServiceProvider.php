<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Core\Themes\ThemeManager;

use App\Core\Tenancy\TenantManager;
use App\Core\Tenancy\SubdomainTenantResolver;
use App\Core\Hooks\HookManager;
use App\Core\Modules\ModuleManager;
use App\Core\Settings\SettingsService;
use App\Core\Content\MetaService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TenantManager::class);
        $this->app->singleton(SubdomainTenantResolver::class);
        $this->app->singleton(HookManager::class);

        $this->app->singleton(ModuleManager::class, fn($app) =>
            new ModuleManager($app->make(TenantManager::class))
        );

        $this->app->singleton(ThemeManager::class, fn($app) =>
            new ThemeManager($app->make(TenantManager::class))
        );

        $this->app->singleton(SettingsService::class);
        $this->app->singleton(MetaService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Si hay tenant resuelto, apuntamos a su tema
        if (app()->bound(ThemeManager::class)) {
            $tm = app(ThemeManager::class);
            View::addLocation($tm->viewPath());
        }
    }
}
