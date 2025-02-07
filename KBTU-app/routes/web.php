<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/about', [MainController::class, 'about']);
Route::get('/home', [MainController::class, 'home']);
