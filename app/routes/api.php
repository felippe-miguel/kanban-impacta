<?php

use Illuminate\Support\Facades\Route;

Route::resource('columns', App\Http\Controllers\ColumnController::class);
Route::resource('cards', App\Http\Controllers\CardController::class);
