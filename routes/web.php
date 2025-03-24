<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/cars', [CarController::class, 'index']);  // แสดงรายการรถ
Route::post('/cars', [CarController::class, 'store']); // สำหรับเพิ่มรถ
Route::delete('/cars/{id}', [CarController::class, 'destroy']);