<?php

namespace App\Core\Modules;

use App\Core\Tenancy\TenantManager;

class ModuleManager
{
    public function __construct(private TenantManager $tenants) {}

    public function activeModules(): array
    {
        $site = $this->tenants->site();
        return $site?->modules ?: ['Core'];
    }

    public function bootActiveModules(): void
    {
        foreach ($this->activeModules() as $module) {
            $provider = $this->providerClass($module);

            if (class_exists($provider)) {
                app()->register($provider);
            }
        }
    }

    private function providerClass(string $module): string
    {
        // Convención: modules/<Module>/src/<Module>ServiceProvider.php
        // Namespace: Modules\<Module>\Src\<Module>ServiceProvider
        return "Modules\\{$module}\\Src\\{$module}ServiceProvider";
    }
}
