<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => '/user'],
        function () {
            Route::get('/', [UserController::class, 'all'])->name('user.all');
            Route::post('add/{id}', [UserController::class, 'index'])->name('user.create');
            Route::post('update/{id}', [UserController::class, 'index'])->name('user.update');
            Route::post('delete/{id}', [UserController::class, 'create'])->name('user.delete');
            Route::get('detail/{id}', [UserController::class, 'store'])->name('user.view');
        });

    Route::group(['prefix' => '/dashboard'],
        function () {
            Route::get('/', [DashboardController::class, 'all'])->name('user.all');
        });
});
Route::post('/auth', [UserController::class, 'auth'])->name('user.auth');
Route::get('/notoken', [UserController::class, 'noToken'])->name('notoken');
