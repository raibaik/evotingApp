<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('login', [AuthController::class, 'login']);
Route::middleware(['admin.api'])->prefix('admin')->group(function () {
    Route::post('user', [AdminController::class, 'register']);
    Route::get('user', [AdminController::class, 'show_register']);
    Route::get('admin', [AdminController::class, 'show_admin']);
    Route::get('user/{id}', [AdminController::class, 'show_register_by_id']);
    Route::put('user/{id}', [AdminController::class, 'update_register']);
    Route::delete('user/{id}', [AdminController::class, 'delete_register']);
    Route::post('angkatan',[AdminController::class, 'angkatan']);
    Route::get('angkatan',[AdminController::class, 'showangkatan']);
    Route::get('angkatan/{id}',[AdminController::class, 'showangkatanbyid']);
    Route::post('calons', [AdminController::class, 'create_calons']);
    Route::put('calons/{id}', [AdminController::class, 'update_calons']);
    Route::delete('calons/{id_calon}', [AdminController::class, 'delete_calons']);
    Route::get('calons', [AdminController::class, 'show_calons']);
    Route::post('pemilihan', [AdminController::class, 'pemilihan']);
    Route::get('pemilihan', [AdminController::class, 'showpemilihan']);
    Route::get('pemilihan/{id}', [AdminController::class, 'showpemilihanbyid']);

});

Route::middleware(['user.api'])->prefix('user')->group(function (){
    Route::post('voting', [UserController::class, 'vote']);
    Route::get('hasilvoting', [UserController::class, 'HasilVoting']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});