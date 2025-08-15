<?php

use Hetbo\Zero\Http\Controllers\ZeroController;
use Illuminate\Support\Facades\Route;

Route::get('/zero', [ZeroController::class, 'index']);
Route::get('/zero/{id}', [ZeroController::class, 'show']);