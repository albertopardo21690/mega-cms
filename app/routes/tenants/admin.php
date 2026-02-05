<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ContentController;

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
});
