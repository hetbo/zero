<?php

use Hetbo\Zero\Http\Controllers\MediaController;
use Hetbo\Zero\Http\Controllers\ZeroController;
use Illuminate\Support\Facades\Route;

/*Route::get('/zero', [ZeroController::class, 'index']);
Route::get('/zero/{id}', [ZeroController::class, 'show']);*/


Route::prefix('zero')
    ->name('media.')
//    ->middleware('web')
    ->group(function () {
        Route::get('/', [MediaController::class, 'index'])->name('index');
        Route::post('/upload', [MediaController::class, 'store'])->name('store');
        Route::delete('/{id}', [MediaController::class, 'destroy'])->name('destroy');
    });