<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Core\Tenancy\TenantManager;
use App\Core\Tenancy\SubdomainTenantResolver;

class IdentifyTenant
{
    public function __construct(
        private TenantManager $tenants,
        private SubdomainTenantResolver $resolver
    ) {}

    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $site = $this->resolver->resolveFromHost($host);

        // Si no hay tenant, puedes:
        // A) bloquear
        // B) redirigir a landing global
        // Aquí: bloqueamos para que sea explícito.
        if (!$site) {
            return response()->json([
                'error' => 'Tenant not found for host',
                'host' => $host,
            ], 404);
        }

        $this->tenants->setSite($site);

        // Opcional: set locale/timezone por tenant
        app()->setLocale($site->locale);
        date_default_timezone_set($site->timezone);

        // Compartir en el container para uso rápido
        app()->instance('currentSite', $site);

        // Autoload settings en memoria para este request
        $settings = app(\App\Core\Settings\SettingsService::class)->autoload($site->id);

        // Disponible en todo el request
        app()->instance('tenantSettings', $settings);

        // También para vistas (como WP: options disponibles globalmente)
        view()->share('tenantSettings', $settings);

        return $next($request);
    }
}
