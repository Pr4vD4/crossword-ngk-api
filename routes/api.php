<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CrosswordController;
use Illuminate\Http\Request;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => ['guest:sanctum']], function () {
   Route::post('/auth', [AuthController::class, 'auth']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
   Route::post('/register', [AuthController::class, 'store']);
   Route::post('/crossword', [CrosswordController::class, 'store']);
});

Route::get('/crossword/', [CrosswordController::class, 'index']);
Route::get('/crossword/{id}', [CrosswordController::class, 'show']);
