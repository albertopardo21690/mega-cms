<?php

namespace App\Core\Menus;

use App\Models\Menu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class MenuService
{
    public function getTree(int $siteId, string $location): array
    {
        $cacheKey = "tenant:{$siteId}:menu:{$location}:tree";

        return Cache::remember($cacheKey, 300, function () use ($siteId, $location) {
            $menu = Menu::query()
                ->where('site_id', $siteId)
                ->where('location', $location)
                ->first();

            if (!$menu) return [];

            $q = $menu->items()
    ->where('site_id', $siteId)
    ->orderBy('sort')
    ->orderBy('id');

            // ✅ Compatible: si existe is_visible, mostramos solo visibles
            if (Schema::hasColumn('menu_items', 'is_visible')) {
                $q->where('is_visible', true);
            }

            $items = $q->get()
                ->map(function ($i) {
                    return [
                        'id' => (int) $i->id,
                        'parent_id' => $i->parent_id ? (int) $i->parent_id : null,
                        'label' => (string) $i->label,
                        'url' => (string) $i->url,
                        'children' => [],
                    ];
                })
                ->toArray();

            return $this->buildTree($items);
        });
    }

    private function buildTree(array $items): array
    {
        $byId = [];
        foreach ($items as $it) {
            $byId[$it['id']] = $it;
        }

        $tree = [];
        foreach ($byId as $id => $it) {
            $pid = $it['parent_id'];
            if ($pid && isset($byId[$pid])) {
                $byId[$pid]['children'][] = &$byId[$id];
            } else {
                $tree[] = &$byId[$id];
            }
        }

        return $tree;
    }

    public function flush(int $siteId, string $location): void
    {
        Cache::forget("tenant:{$siteId}:menu:{$location}:tree");
    }
}
