<?php

namespace App\Core\Themes;

use App\Core\Tenancy\TenantManager;

class ThemeManager
{
    public function __construct(private TenantManager $tenants) {}

    public function theme(): string
    {
        return $this->tenants->site()?->theme ?: 'default';
    }

    public function viewPath(): string
    {
        // Cargaremos vistas desde /themes/<theme>/views
        return base_path("themes/{$this->theme()}/views");
    }
}
