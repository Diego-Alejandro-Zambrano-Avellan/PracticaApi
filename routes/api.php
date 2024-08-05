<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::group(['middleware' => ["auth:sanctum"]], function () {
    Route::resource('/users',UsuarioController::class);
});
/** Route::get('/users', function (Request $request) {
    Route::resource('/users',UsuarioController::class);
    return $request->user();
   })->middleware('auth:sanctum');
*/


Route::post('users/register',[UsuarioController::class,'registar']);
Route::post('users/login',[UsuarioController::class,'login']);