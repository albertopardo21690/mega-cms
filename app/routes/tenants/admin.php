<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TaxonomyController;
use App\Http\Controllers\Admin\MediaController;

Route::middleware(['tenant'])->prefix('admin')->group(function () {

    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Pages y Posts (type = page | post)
    Route::get('/{type}', [ContentController::class, 'index'])
        ->whereIn('type', ['page','post'])
        ->name('admin.contents.index');

    Route::get('/{type}/create', [ContentController::class, 'create'])
        ->whereIn('type', ['page','post'])
        ->name('admin.contents.create');

    Route::post('/{type}', [ContentController::class, 'store'])
        ->whereIn('type', ['page','post'])
        ->name('admin.contents.store');

    Route::get('/{type}/{id}', [ContentController::class, 'edit'])
        ->whereIn('type', ['page','post'])
        ->whereNumber('id')
        ->name('admin.contents.edit');

    Route::post('/{type}/{id}', [ContentController::class, 'update'])
        ->whereIn('type', ['page','post'])
        ->whereNumber('id')
        ->name('admin.contents.update');

    Route::post('/{type}/{id}/delete', [ContentController::class, 'destroy'])
        ->whereIn('type', ['page','post'])
        ->whereNumber('id')
        ->name('admin.contents.delete');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings/save', [SettingsController::class, 'save'])->name('admin.settings.save');
    Route::post('/settings/delete', [SettingsController::class, 'delete'])->name('admin.settings.delete');
    Route::post('/settings/flush', [SettingsController::class, 'flush'])->name('admin.settings.flush');

    // Taxonomies
    Route::get('/taxonomies', [TaxonomyController::class, 'index'])->name('admin.taxonomies');
    Route::get('/taxonomies/{taxonomyKey}', [TaxonomyController::class, 'terms'])->name('admin.taxonomies.terms');
    Route::post('/taxonomies/{taxonomyKey}/save', [TaxonomyController::class, 'saveTerm'])->name('admin.taxonomies.terms.save');
    Route::post('/taxonomies/{taxonomyKey}/delete', [TaxonomyController::class, 'deleteTerm'])->name('admin.taxonomies.terms.delete');

    // Media
    Route::get('/media', [MediaController::class, 'index'])->name('admin.media');
    Route::post('/media/upload', [MediaController::class, 'upload'])->name('admin.media.upload');
    Route::post('/media/delete', [MediaController::class, 'delete'])->name('admin.media.delete');
});
