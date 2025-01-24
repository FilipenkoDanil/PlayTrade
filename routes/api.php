<?php

use App\Http\Controllers\API\AttributeController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\GameController;
use App\Http\Controllers\API\OfferController;
use App\Http\Controllers\API\ServerController;
use App\Http\Controllers\API\StatusController;
use App\Http\Controllers\API\UnitController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('games', GameController::class);
Route::apiResource('units', UnitController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('attributes', AttributeController::class);
Route::apiResource('servers', ServerController::class);
Route::apiResource('offers', OfferController::class);
Route::apiResource('statuses', StatusController::class);
