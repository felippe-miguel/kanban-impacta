<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('boards.index');
    });
	Route::resource('boards', App\Http\Controllers\BoardController::class);
    Route::post('boards/{board}/columns', [App\Http\Controllers\ColumnController::class, 'store'])->name('columns.store');
    Route::delete('boards/{board}/columns/{column}', [App\Http\Controllers\ColumnController::class, 'destroy'])->name('columns.destroy');

    Route::post('boards/{board}/cards', [App\Http\Controllers\CardController::class, 'store'])->name('cards.store');
    Route::delete('boards/{board}/cards/{column}', [App\Http\Controllers\CardController::class, 'destroy'])->name('cards.destroy');
});
