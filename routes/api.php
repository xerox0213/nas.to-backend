<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiscoverArticleController;
use App\Http\Controllers\UserArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::delete('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('articles', UserArticleController::class)->only('store');
});

Route::get("/articles/discover", [DiscoverArticleController::class, 'index'])->name("discover-articles.index");
