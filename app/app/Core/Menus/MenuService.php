<?php

namespace App\Core\Menus;

use App\Models\Menu;
use Illuminate\Support\Facades\Cache;

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

            $items = $menu->items()
                ->where('site_id', $siteId)      // ✅ tenant-safe
                ->where('is_visible', true)      // ✅ visibles
                ->orderBy('sort')
                ->orderBy('id')
                ->get()
                ->map(fn($i) => [
                    'id' => (int) $i->id,
                    'parent_id' => $i->parent_id ? (int) $i->parent_id : null,
                    'label' => (string) $i->label,
                    'url' => (string) $i->url,
                    'children' => [],
                ])->toArray();

            return $this->buildTree($items);
        });
    }

    private function buildTree(array $items): array
    {
        $byId = [];
        foreach ($items as $it) $byId[$it['id']] = $it;

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
