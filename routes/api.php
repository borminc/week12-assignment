<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ToDoItemController;

use App\Models\User;
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

// public routes
Route::get('public-items', [ToDoItemController::class, 'index']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// private routes (need auth)
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('user', function() { return Auth::user();});    
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('user-items', [ToDoItemController::class, 'listUserItems']);
    Route::post('items', [ToDoItemController::class, 'addItem']);
    Route::put('items/{id}', [ToDoItemController::class, 'editItem']);
    Route::delete('items/{id}', [ToDoItemController::class, 'destroy']);    
});
