<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('items', ItemController::class)->names([
    'index' => 'items.index',
    'create' => 'items.create',
    'store' => 'items.store',
    'show' => 'items.show',
    'edit' => 'items.edit',
    'update' => 'items.update',
    'destroy' => 'items.destroy',
]);
