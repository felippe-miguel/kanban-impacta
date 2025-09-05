<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
	Route::resource('boards', App\Http\Controllers\BoardController::class);
});
