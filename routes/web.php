<?php

use Hetbo\Zero\Http\Controllers\AssetController;
use Hetbo\Zero\Http\Controllers\CarrotComponentController;
use Hetbo\Zero\Http\Controllers\CarrotController;
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

Route::middleware(['web', 'auth'])
    ->prefix('carrots')
    ->as('carrots.')
    ->group(function () {
        Route::get('/', [CarrotController::class, 'index'])->name('index');
        Route::post('/', [CarrotController::class, 'store'])->name('store');
        Route::delete('/{carrot}', [CarrotController::class, 'destroy'])->name('destroy');
    });


Route::prefix('carrot-package')->as('carrot-package.')->middleware('web')->group(function () {
    // Attaches an existing carrot to any model
    Route::post('/attach', [CarrotComponentController::class, 'attach'])->name('attach');

    // Detaches a carrot from any model
    Route::post('/detach', [CarrotComponentController::class, 'detach'])->name('detach');

    // Creates a new carrot AND attaches it
    Route::post('/create-and-attach', [CarrotComponentController::class, 'createAndAttach'])->name('create-and-attach');
});

// Asset Serving Route
Route::get('/carrot-package/carrots.js', [AssetController::class, 'source'])->name('carrot-package.assets.js');

// New Routes
Route::prefix('the-carrots')->as('the-carrots.')->middleware('web')->group(function () {
    Route::get('/', [])->name('index');
});