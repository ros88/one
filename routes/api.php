<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('/v1')->group(function() {
    Route::post('/register', [UserController::class, 'store']);
    Route::post('/login', [UserController::class, 'login']);

    Route::get('/users', [UserController::class, 'index'])->middleware(['auth:sanctum']);
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware(['auth:sanctum']);
    Route::get('/authorizationMsg', [UserController::class, 'notAuthorization'])->name('authorization');
    Route::delete('/users/{id}', [UserController::class ,'delete'])->middleware(['auth:sanctum']);
});
 