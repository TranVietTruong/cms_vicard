<?php



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


Route::group(['middleware' => 'api', 'prefix' => 'v1.0/auth', 'namespace' => 'api'], function (){
    Route::post('login', [\App\Http\Controllers\AuthController::class,'login']);
    Route::post('register', [\App\Http\Controllers\AuthController::class,'register']);
});

Route::group(['middleware' => ['api','auth:api'], 'prefix' => 'v1.0/auth', 'namespace' => 'api'], function (){
    Route::get('me', [\App\Http\Controllers\AuthController::class,'me']);
    Route::post('logout', [\App\Http\Controllers\AuthController::class,'logout']);
});

Route::group(['middleware' => ['api','auth:api'], 'prefix' => 'v1.0', 'namespace' => 'api'], function (){
    Route::get('order', [\App\Http\Controllers\OrderController::class,'listOrder']);
});
