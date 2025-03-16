<?php

use App\Http\Controllers\API\AttributeController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\Chat\ChatController;
use App\Http\Controllers\API\Chat\MessageController;
use App\Http\Controllers\API\DealController;
use App\Http\Controllers\API\GameController;
use App\Http\Controllers\API\Moder\DisputeController;
use App\Http\Controllers\API\Moder\ModerController;
use App\Http\Controllers\API\OfferController;
use App\Http\Controllers\API\Payment\PaymentController;
use App\Http\Controllers\API\RatingController;
use App\Http\Controllers\API\ServerController;
use App\Http\Controllers\API\StatusController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UnitController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WithdrawalController;
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

Route::apiResource('games', GameController::class)->only('index', 'show');
Route::apiResource('units', UnitController::class)->only('index', 'show');
Route::apiResource('categories', CategoryController::class)->only('index', 'show');
Route::apiResource('attributes', AttributeController::class)->only('index', 'show');
Route::apiResource('servers', ServerController::class)->only('index', 'show');
Route::apiResource('offers', OfferController::class)->only('show');
Route::apiResource('statuses', StatusController::class)->only('show');
Route::apiResource('ratings', RatingController::class)->only('index', 'show');
Route::apiResource('users', UserController::class)->only('show');

Route::post('/payment/serviceUrl', [PaymentController::class, 'service']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/payment/create', [PaymentController::class, 'payment']);

    Route::get('offers', [OfferController::class, 'index']);
    Route::post('offers', [OfferController::class, 'store']);
    Route::put('offers/{offer}', [OfferController::class, 'update']);
    Route::delete('offers/{offer}', [OfferController::class, 'destroy']);

    Route::apiResource('deals', DealController::class)->except(['update', 'destroy']);
    Route::get('orders', [DealController::class, 'orders']);
    Route::get('sales', [DealController::class, 'sales']);
    Route::patch('deals/{deal}/confirm', [DealController::class, 'confirm'])->middleware('check.deal.status');
    Route::patch('deals/{deal}/cancel', [DealController::class, 'cancel'])->middleware('check.deal.status');
    Route::patch('deals/{deal}/dispute', [DealController::class, 'dispute'])->middleware('check.deal.status');

    Route::apiResource('chats', ChatController::class)->except(['destroy', 'update']);
    Route::post('chats/find', [ChatController::class, 'find']);
    Route::post('/messages', [MessageController::class, 'store']);

    Route::post('ratings', [RatingController::class, 'store']);
    Route::put('ratings/{rating}', [RatingController::class, 'update']);
    Route::delete('ratings/{rating}', [RatingController::class, 'destroy']);

    Route::apiResource('transactions', TransactionController::class)->only('index');
    Route::apiResource('withdrawals', WithdrawalController::class)->only('store');
    Route::post('withdrawals/{withdrawal}/cancel', [WithdrawalController::class, 'cancel']);


    Route::group(['middleware' => 'role:moder'], function () {
        Route::prefix('moder')->group(function () {
            Route::post('chats/find', [ModerController::class, 'findChat']);
            Route::post('messages', [ModerController::class, 'sendMessage']);
            Route::get('chats/{chat}', [ModerController::class, 'showChat']);

            Route::post('disputes/refund-buyer', [DisputeController::class, 'refundBuyer']);
            Route::post('disputes/refund-seller', [DisputeController::class, 'refundSeller']);
            Route::post('disputes/refund-fifty', [DisputeController::class, 'refundFiftyFifty']);
        });

        Route::apiResource('games', GameController::class)->except('index', 'show');
        Route::get('trash/games', [GameController::class, 'trashed']);
        Route::apiResource('units', UnitController::class)->except('index', 'show');
        Route::apiResource('categories', CategoryController::class)->except('index', 'show');
        Route::apiResource('attributes', AttributeController::class)->except('index', 'show');
        Route::apiResource('servers', ServerController::class)->except('index', 'show');
        Route::apiResource('statuses', StatusController::class)->except('show');

        Route::get('disputes', [DisputeController::class, 'index']);
    });
});
