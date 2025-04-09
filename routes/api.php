<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/v1'], function () {
    Route::group(['prefix' => '/auth'], function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::get('/test', function () {
    return response()->json([
        'message' => 'Success'
    ]);
});

