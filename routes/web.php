<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Webpatser\Uuid\Uuid;

Route::get('/view/email', [\App\Http\Controllers\UserController::class, 'forgetPassword']);

Route::get('/preview/{name}',[\App\Http\Controllers\PreviewController::class, 'preview']);
Route::get('/view/{name}',[\App\Http\Controllers\ViewController::class, 'index']);
Route::get('/save-contact/{userId}', [\App\Http\Controllers\VcardController::class, 'saveContact']);



Route::any('/{any}', function() {
    return view('index');
})->where('any', '.*');
