<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL; // ✅ IMPORTANTE
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

        if (!$site) {
            return response()->json([
                'error' => 'Tenant not found for host',
                'host' => $host,
            ], 404);
        }

        $this->tenants->setSite($site);

        app()->setLocale($site->locale);
        date_default_timezone_set($site->timezone);

        app()->instance('currentSite', $site);

        $settings = app(\App\Core\Settings\SettingsService::class)->autoload($site->id);
        app()->instance('tenantSettings', $settings);
        view()->share('tenantSettings', $settings);

        // ✅ Forzar host correcto para URLs y Storage::url
        $root = $request->getSchemeAndHttpHost();
        config(['app.url' => $root]);
        config(['filesystems.disks.public.url' => $root . '/storage']);
        URL::forceRootUrl($root);

        return $next($request);
    }
}
