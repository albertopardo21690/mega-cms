<?php

use Illuminate\Support\Facades\Route;
use App\Core\Hooks\HookManager;
use App\Core\Modules\ModuleManager;
use App\Models\Content;

Route::middleware(['tenant'])->group(function () {

    Route::get('/', function () {
        /** @var \App\Models\Site $site */
        $site = app('currentSite');

        // Boot módulos activos (plugins) por tenant
        app(ModuleManager::class)->bootActiveModules();

        // Hooks estilo WP
        app(HookManager::class)->doAction('init', $site);

        $title = app(HookManager::class)->applyFilters('site_title', $site->name, $site);

        // Render desde el tema activo: themes/<theme>/views/home.blade.php
        return view('home', [
            'site' => $site,
            'title' => $title,
        ]);
    });

    Route::get('/{slug}', function (string $slug) {
        $site = app('currentSite');

        app(\App\Core\Modules\ModuleManager::class)->bootActiveModules();
        app(\App\Core\Hooks\HookManager::class)->doAction('init', $site);

        $page = Content::query()
            ->where('site_id', $site->id)
            ->where('type', 'page')
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        $meta = $page->meta()->pluck('meta_value','meta_key')->toArray();

        $title = app(\App\Core\Hooks\HookManager::class)->applyFilters('the_title', $page->title, $page);
        $html  = app(\App\Core\Hooks\HookManager::class)->applyFilters('the_content', $page->content ?? '', $page);

        return view('page', [
            'site' => $site,
            'page' => $page,
            'title' => $title,
            'html' => $html,
            'meta' => $meta,
        ]);
    });

});
