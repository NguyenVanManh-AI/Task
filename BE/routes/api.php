<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('book')->controller(BookController::class)->group(function () {
    Route::post('/add', 'add');
    Route::post('update/{id}', 'edit');
    Route::delete('delete/{id}', 'delete');
    Route::delete('deletes', 'deleteMany');
    Route::get('/', 'all');
});

Route::prefix('category')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'all');
});
