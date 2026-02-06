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
                ->where('is_visible', true)
                ->orderBy('sort')
                ->orderBy('id')
                ->get()
                ->map(fn($i) => [
                    'id' => $i->id,
                    'parent_id' => $i->parent_id,
                    'label' => $i->label,
                    'url' => $i->url,
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
