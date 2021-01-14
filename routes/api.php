<?php

use App\Http\Controllers\Api\Auth\LoginJwtController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RealStateController;
use App\Http\Controllers\Api\RealStatePhotoController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    // LOGIN
    Route::post('login', [LoginJwtController::class, 'login'])->name('login');
    // REAL-STATES INDEX E SHOW
    Route::name('real_state.')->prefix('real-states')->group(function () {
        Route::get('real-states', [RealStateController::class, 'index']);
        Route::get('/{id}', [RealStateController::class, 'show']);
    });
    // MIDDLEWARE JWT.AUTH
    Route::group(['middleware' => ['jwt.auth']], function () {
        // REAL_STATE
        Route::name('real_state.')->prefix('real-states')->group(function () {
            Route::post('/', [RealStateController::class, 'store']);
            Route::put('/{id}', [RealStateController::class, 'update']);
            Route::delete('/{id}', [RealStateController::class, 'delete']);
        });
        // USERS
        Route::name('user.')->group(function () {
            Route::get('users/real-states', [UserController::class, 'getRealStates']);
            Route::resource('users', UserController::class);
        });
        // CATEGORIES
        Route::name('category.')->group(function () {
            Route::resource('categories', CategoryController::class);
        });
        // REAL_STATE_PHOTOS
        Route::name('photo.')->prefix('photo')->group(function () {
            Route::put('/{photoId}/{realStateId}', [RealStatePhotoController::class, 'setThumb'])->name('update');
            Route::delete('/{photoId}', [RealStatePhotoController::class, 'remove'])->name('delete');
        });
    });
});
