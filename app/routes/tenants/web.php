<?php

use Illuminate\Support\Facades\Route;
use App\Core\Hooks\HookManager;
use App\Core\Modules\ModuleManager;
use App\Models\Content;

Route::middleware(['tenant'])->group(function () {

    // HOME
    Route::get('/', function () {
        $site = app('currentSite');

        app(ModuleManager::class)->bootActiveModules();
        app(HookManager::class)->doAction('init', $site);

        $title = app(HookManager::class)->applyFilters('site_title', $site->name, $site);

        return view('home', [
            'site' => $site,
            'title' => $title,
        ]);
    });

    // BLOG (antes que /{slug})
    Route::get('/blog', function () {
        $site = app('currentSite');

        $posts = Content::query()
            ->where('site_id', $site->id)
            ->where('type', 'post')
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->paginate(10);

        return view('blog-index', compact('site', 'posts'));
    });

    Route::get('/blog/{slug}', function (string $slug) {
        $site = app('currentSite');

        $post = Content::query()
            ->where('site_id', $site->id)
            ->where('type', 'post')
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        $meta  = $post->meta()->pluck('meta_value', 'meta_key')->toArray();
        $title = $post->title;
        $html  = $post->content ?? '';

        return view('blog-post', compact('site', 'post', 'meta', 'title', 'html'));
    });

    // PAGES (al final) + blindaje para no capturar rutas reservadas
    Route::get('/{slug}', function (string $slug) {
        $site = app('currentSite');

        app(ModuleManager::class)->bootActiveModules();
        app(HookManager::class)->doAction('init', $site);

        $page = Content::query()
            ->where('site_id', $site->id)
            ->where('type', 'page')
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        $meta = $page->meta()->pluck('meta_value', 'meta_key')->toArray();

        $title = app(HookManager::class)->applyFilters('the_title', $page->title, $page);
        $html  = app(HookManager::class)->applyFilters('the_content', $page->content ?? '', $page);

        return view('page', [
            'site' => $site,
            'page' => $page,
            'title' => $title,
            'html' => $html,
            'meta' => $meta,
        ]);
    })->where('slug', '^(?!blog$|admin$|api$|storage$).+');

});
