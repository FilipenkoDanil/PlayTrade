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

Route::apiResource('games', GameController::class);
Route::get('trash/games', [GameController::class, 'trashed']);
Route::apiResource('units', UnitController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('attributes', AttributeController::class);
Route::apiResource('servers', ServerController::class);
Route::apiResource('offers', OfferController::class);
Route::apiResource('statuses', StatusController::class);
Route::apiResource('deals', DealController::class)->except(['update', 'destroy']);
Route::get('orders', [DealController::class, 'orders']);
Route::get('sales', [DealController::class, 'sales']);
Route::patch('deals/{deal}/confirm', [DealController::class, 'confirm'])->middleware('check.deal.status');
Route::patch('deals/{deal}/cancel', [DealController::class, 'cancel'])->middleware('check.deal.status');
Route::patch('deals/{deal}/dispute', [DealController::class, 'dispute'])->middleware('check.deal.status');
Route::apiResource('ratings', RatingController::class);

Route::apiResource('transactions', TransactionController::class)->except('destroy', 'update', 'show', 'store');
Route::apiResource('withdrawals', WithdrawalController::class);
Route::post('withdrawals/{withdrawal}/cancel', [WithdrawalController::class, 'cancel']);

Route::apiResource('chats', ChatController::class)->except(['destroy', 'update']);
Route::post('chats/find', [ChatController::class, 'find']);
Route::post('/messages', [MessageController::class, 'store']);

Route::post('moder/chats/find', [ModerController::class, 'findChat']);
Route::post('moder/messages', [ModerController::class, 'sendMessage']);
Route::get('moder/chats/{chat}', [ModerController::class, 'showChat']);

Route::post('moder/disputes/refund-buyer', [DisputeController::class, 'refundBuyer']);
Route::post('moder/disputes/refund-seller', [DisputeController::class, 'refundSeller']);
Route::post('moder/disputes/refund-fifty', [DisputeController::class, 'refundFiftyFifty']);

Route::get('disputes', [DisputeController::class, 'index']);

Route::post('/payment/create', [PaymentController::class, 'payment']);
Route::post('/payment/serviceUrl', [PaymentController::class, 'service']);


Route::apiResource('users', UserController::class);
