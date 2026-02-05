<?php

namespace App\Core\Tenancy;

use App\Models\Site;

class TenantManager
{
    private ?Site $site = null;

    public function setSite(?Site $site): void
    {
        $this->site = $site;
    }

    public function site(): ?Site
    {
        return $this->site;
    }

    public function id(): ?int
    {
        return $this->site?->id;
    }
}
