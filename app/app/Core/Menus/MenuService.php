<?php

namespace App\Core\Menus;

use Illuminate\Support\Facades\DB;

class MenuService
{
    public function byLocation(int $siteId, string $location)
    {
        $menu = DB::table('menus')
            ->where('site_id',$siteId)
            ->where('location',$location)
            ->first();

        if (!$menu) return collect();

        return DB::table('menu_items')
            ->where('site_id',$siteId)
            ->where('menu_id',$menu->id)
            ->orderBy('sort')
            ->get();
    }
}
