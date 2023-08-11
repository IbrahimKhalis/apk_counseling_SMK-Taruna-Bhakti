<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
    
// });

    Route::group(['middleware' => ['auth:sanctum']], function(){

        // user
        Route::get('user',[AuthController::class,'user']);
        Route::post('logout',[AuthController::class,'logout']);
        Route::post('store', [AuthController::class, 'store']);
        Route::get('create', [AuthController::class, 'create']);

    });

Route::post('auth/login', [AuthController::class, 'Login']);

// Route::get('/siswa', AuthController::class, 'index');

// Route::get('/siswa/{id}', AuthController::class, 'show');

Route::get('/history/{id}', [AuthController::class, 'history']);



