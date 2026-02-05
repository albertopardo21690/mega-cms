<?php

namespace App\Core\Settings;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public function autoload(int $siteId): array
    {
        // Cache por tenant (igual filosofía WP options autoload)
        return Cache::remember($this->cacheKey($siteId), now()->addMinutes(30), function () use ($siteId) {
            return Setting::query()
                ->where('site_id', $siteId)
                ->where('autoload', true)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    public function get(int $siteId, string $key, $default = null)
    {
        // Primero miramos autoload cache
        $all = $this->autoload($siteId);
        if (array_key_exists($key, $all)) return $all[$key];

        // Si no está en autoload, buscamos en DB (sin cache)
        return Setting::query()
            ->where('site_id', $siteId)
            ->where('key', $key)
            ->value('value') ?? $default;
    }

    public function set(int $siteId, string $key, ?string $value, bool $autoload = false): void
    {
        Setting::updateOrCreate(
            ['site_id' => $siteId, 'key' => $key],
            ['value' => $value, 'autoload' => $autoload]
        );

        // Si tocamos settings, invalidamos cache del tenant
        Cache::forget($this->cacheKey($siteId));
    }

    public function forget(int $siteId, string $key): void
    {
        Setting::query()
            ->where('site_id', $siteId)
            ->where('key', $key)
            ->delete();

        Cache::forget($this->cacheKey($siteId));
    }

    public function flush(int $siteId): void
    {
        Cache::forget($this->cacheKey($siteId));
    }

    private function cacheKey(int $siteId): string
    {
        return "tenant:{$siteId}:settings:autoload";
    }
}
