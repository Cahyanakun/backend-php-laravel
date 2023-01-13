<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ArticleController;

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
Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function(){
    Route::group(['prefix' => 'sanctum'], function(){
        Route::get('/loggedIn', [AuthController::class, 'loggedIn']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'user', 'middleware' => ['can:isAdmin']], function(){
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show'])->where('id', '[0-9]+');
        Route::put('/{id}', [UserController::class, 'update'])->where('id', '[0-9]+');
        Route::delete('/{id}', [UserController::class, 'destroy'])->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'article'], function(){
        Route::get('/', [ArticleController::class, 'index']);
        Route::post('/', [ArticleController::class, 'store']);
        Route::get('/{id}', [ArticleController::class, 'show'])->where('id', '[0-9]+');
        Route::put('/{id}', [ArticleController::class, 'update'])->where('id', '[0-9]+');
        Route::delete('/{id}', [ArticleController::class, 'destroy'])->where('id', '[0-9]+');
    });
});
