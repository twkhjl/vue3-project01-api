<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TttController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });






Route::group([
    'middleware' => 'api',
    'prefix' => 'admin'
], function ($router) {
    Route::post('test', [AuthController::class,'test']);

    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});



Route::group([
    'middleware' => 'api',
    'prefix' => 'category'
], function ($router) {
    Route::get('all', [CategoryController::class,'all']);
    Route::get('show/{id}', [CategoryController::class,'show']);
    Route::post('store', [CategoryController::class,'store']);
    Route::post('update', [CategoryController::class,'update']);
    Route::post('destroy', [CategoryController::class,'destroy']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'product'
], function ($router) {
    Route::get('all', [ProductController::class,'all']);
    Route::get('paginate', [ProductController::class,'paginate']);
});

