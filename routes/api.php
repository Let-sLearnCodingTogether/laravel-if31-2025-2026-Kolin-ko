<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\UserManagementController;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function(){
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/login', [AuthenticationController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [AuthenticationController::class,'logout']);

    Route::get('spot/{spot}/reviews', [ReviewController::class, 'reviews']);

    Route::apiResource('spot', SpotController::class);

    Route::apiResource('review', ReviewController::class)
        ->only([
            'store',
            'destroy'
        ])
        // Middleware hanya : user yang bisa membuat review
        ->middlewareFor(['store'], 'ensureUserHasRole:USER')
        // Middleware hanya : admin yang bisa menghapus review
        ->middlewareFor(['destroy'], 'ensureUserHasRole:ADMIN');

    Route::apiResource('management', UserManagementController::class)
    ->only([
        'update',
        'store'
    ]);
});



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
