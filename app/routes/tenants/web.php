<?php

use Illuminate\Support\Facades\Route;
use App\Core\Hooks\HookManager;
use App\Core\Modules\ModuleManager;

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

});
