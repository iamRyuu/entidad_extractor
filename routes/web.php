<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntityController;

Route::get('/', function () {
    return view('entities');
});

Route::post('/extract-entities', [EntityController::class, 'extract']);

