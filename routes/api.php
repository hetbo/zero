<?php

use Illuminate\Support\Facades\Route;
use Hetbo\Zero\Http\Controllers\CarrotController;
use Hetbo\Zero\Http\Controllers\CarrotableController;

// Carrot routes
Route::prefix('api/carrots')->group(function () {
    Route::get('/', [CarrotController::class, 'index'])->name('carrots.index');
    Route::post('/', [CarrotController::class, 'store'])->name('carrots.store');
    Route::get('/search/name', [CarrotController::class, 'searchByName'])->name('carrots.search.name');
    Route::get('/search/length', [CarrotController::class, 'searchByLength'])->name('carrots.search.length');
    Route::get('/{id}', [CarrotController::class, 'show'])->name('carrots.show');
    Route::put('/{id}', [CarrotController::class, 'update'])->name('carrots.update');
    Route::delete('/{id}', [CarrotController::class, 'destroy'])->name('carrots.destroy');
});

// Carrotable routes (polymorphic relationships)
Route::prefix('api/carrotables')->group(function () {
    Route::post('/attach', [CarrotableController::class, 'attach'])->name('carrotables.attach');
    Route::post('/detach', [CarrotableController::class, 'detach'])->name('carrotables.detach');
    Route::post('/sync', [CarrotableController::class, 'sync'])->name('carrotables.sync');
    Route::get('/carrots', [CarrotableController::class, 'getCarrotsByRole'])->name('carrotables.carrots.role');
    Route::get('/carrots/all', [CarrotableController::class, 'getAllCarrots'])->name('carrotables.carrots.all');
    Route::get('/roles', [CarrotableController::class, 'getRoles'])->name('carrotables.roles');
});
