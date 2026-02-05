<?php

namespace App\Core\Tenancy;

use App\Models\Site;

class SubdomainTenantResolver
{
    public function resolveFromHost(string $host): ?Site
    {
        // host: cliente1.tudominio.local
        $host = strtolower(trim($host));
        $parts = explode('.', $host);

        // mínimo: sub + root + tld => 3 partes (cliente1 + tudominio + local)
        if (count($parts) < 3) {
            return null;
        }

        $subdomain = $parts[0];

        // Evitar capturar "www"
        if (in_array($subdomain, ['www', 'admin', 'api'])) {
            return null;
        }

        return Site::query()
            ->where('subdomain', $subdomain)
            ->where('is_active', true)
            ->first();
    }
}
