<?php

require __DIR__.'/tenants/web.php';

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
