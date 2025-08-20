<?php

use Hetbo\Zero\Http\Controllers\AssetController;
use Hetbo\Zero\Http\Controllers\ReactController;
use Hetbo\Zero\Http\Controllers\WebCarrotController;
use Illuminate\Support\Facades\Route;


Route::prefix('carrots')->name('carrots.')->group(function () {
    Route::get('/', [WebCarrotController::class, 'index'])->name('index');
    Route::get('/modal/{model_type}/{model_id}/{role}', [WebCarrotController::class, 'modal'])->name('modal');
    Route::get('/load-more/{model_type}/{model_id}/{role}', [WebCarrotController::class, 'loadMore'])->name('load-more');
    Route::post('/attach/{model_type}/{model_id}', [WebCarrotController::class, 'attach'])->name('attach');
    Route::delete('/detach/{model_type}/{model_id}/{carrot_id}/{role}', [WebCarrotController::class, 'detach'])->name('detach');
    Route::get('/component-content/{model_type}/{model_id}/{role}', [WebCarrotController::class, 'componentContent'])
        ->name('component-content');
});

// Asset Serving Route
Route::get('/carrot-package/carrots.js', [AssetController::class, 'source'])->name('carrot-package.assets.js');

Route::prefix('zero')->group(function () {
    Route::get('/', [ReactController::class, 'index'])->name('zero');
    Route::get('/api/files', [ReactController::class, 'getFiles']);
    Route::post('/api/upload', [ReactController::class, 'upload']);
    Route::delete('/api/files/{file}', [ReactController::class, 'delete']);
});