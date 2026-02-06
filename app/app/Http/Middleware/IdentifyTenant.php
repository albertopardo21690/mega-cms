<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Core\Tenancy\TenantManager;
use App\Core\Tenancy\SubdomainTenantResolver;
use Illuminate\Support\Facades\View;

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

        $theme = $site->theme ?? 'default';

        // Views del theme
        View::addLocation(base_path("themes/{$theme}/views"));

        // Assets helper
        app()->instance('themePath', "/themes/{$theme}");

        $menuService = app(\App\Core\Menus\MenuService::class);

$headerMenu = $menuService->getTree($site->id, 'header');
$footerMenu = $menuService->getTree($site->id, 'footer');

view()->share('headerMenu', $headerMenu);
view()->share('footerMenu', $footerMenu);

        return $next($request);
    }
}
