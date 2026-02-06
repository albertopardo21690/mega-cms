<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TaxonomyController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\RecipeController;
use App\Http\Controllers\Admin\MenuController;

Route::get('/__ADMIN_OK', fn() => 'ADMIN ROUTES OK');

Route::middleware(['tenant'])->prefix('admin')->group(function () {

    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // ✅ RUTAS FIJAS PRIMERO
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings/save', [SettingsController::class, 'save'])->name('admin.settings.save');
    Route::post('/settings/delete', [SettingsController::class, 'delete'])->name('admin.settings.delete');
    Route::post('/settings/flush', [SettingsController::class, 'flush'])->name('admin.settings.flush');

    Route::get('/taxonomies', [TaxonomyController::class, 'index'])->name('admin.taxonomies');
    Route::get('/taxonomies/{taxonomyKey}', [TaxonomyController::class, 'terms'])->name('admin.taxonomies.terms');
    Route::post('/taxonomies/{taxonomyKey}/save', [TaxonomyController::class, 'saveTerm'])->name('admin.taxonomies.terms.save');
    Route::post('/taxonomies/{taxonomyKey}/delete', [TaxonomyController::class, 'deleteTerm'])->name('admin.taxonomies.terms.delete');

    Route::get('/media', [MediaController::class, 'index'])->name('admin.media');
    Route::post('/media/upload', [MediaController::class, 'upload'])->name('admin.media.upload');
    Route::post('/media/delete', [MediaController::class, 'delete'])->name('admin.media.delete');

    Route::get('/recipes', [RecipeController::class,'index'])->name('admin.recipes');
    Route::post('/recipes/install', [RecipeController::class,'install'])->name('admin.recipes.install');

    Route::get('/menus', [MenuController::class,'index'])->name('admin.menus');
    Route::get('/menus/{location}', [MenuController::class,'edit'])
        ->where('location', '^(header|footer)$')->name('admin.menus.edit');
    Route::post('/menus/{location}/add', [MenuController::class,'addItem'])
        ->where('location', '^(header|footer)$')->name('admin.menus.add');
    Route::post('/menus/{location}/save', [MenuController::class,'saveOrder'])
        ->where('location', '^(header|footer)$')->name('admin.menus.save');
    Route::post('/menus/{location}/delete', [MenuController::class,'deleteItem'])
        ->where('location', '^(header|footer)$')->name('admin.menus.delete');

    // ✅ CONTENIDO AL FINAL
    Route::get('/{type}', [ContentController::class, 'index'])
        ->where('type', '^(page|post)$')->name('admin.contents.index');
    Route::get('/{type}/create', [ContentController::class, 'create'])
        ->where('type', '^(page|post)$')->name('admin.contents.create');
    Route::post('/{type}', [ContentController::class, 'store'])
        ->where('type', '^(page|post)$')->name('admin.contents.store');
    Route::get('/{type}/{id}', [ContentController::class, 'edit'])
        ->where('type', '^(page|post)$')->whereNumber('id')->name('admin.contents.edit');
    Route::post('/{type}/{id}', [ContentController::class, 'update'])
        ->where('type', '^(page|post)$')->whereNumber('id')->name('admin.contents.update');
    Route::post('/{type}/{id}/delete', [ContentController::class, 'destroy'])
        ->where('type', '^(page|post)$')->whereNumber('id')->name('admin.contents.delete');
});
