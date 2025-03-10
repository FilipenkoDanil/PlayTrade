<?php

namespace App\Http\Controllers\API\Moder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deal\DisputeDealRequest;
use App\Http\Resources\DealResource;
use App\Models\Deal;
use App\Models\Status;
use App\Services\ChatService;
use App\Services\DealService;
use App\Services\MessageService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\DB;

class DisputeController extends Controller
{
    public function __construct(
        private readonly TransactionService $transactionService,
        private readonly DealService $dealService,
        private readonly ChatService $chatService,
        private readonly MessageService $messageService)
    {
    }

    public function index()
    {
        return DealResource::collection(Deal::where('status_id', Status::DEAL_DISPUTED)->get());
    }

    public function refundBuyer(DisputeDealRequest $request)
    {
        $deal = Deal::findOrFail($request->deal_id);

        if ($deal->status_id !== Status::DEAL_DISPUTED) {
            return response()->json(['error' => 'Deal is not disputed'], 400);
        }

        DB::transaction(function () use ($deal) {
            $this->dealService->cancel($deal);

            $chat = $this->chatService->createOrGetChat($deal->offer->seller->id, $deal->buyer_id);
            $this->messageService->sendDealNotification($deal, $chat, 'refundBuyer');
        });
    }

    public function refundSeller(DisputeDealRequest $request)
    {
        $deal = Deal::findOrFail($request->deal_id);

        if ($deal->status_id !== Status::DEAL_DISPUTED) {
            return response()->json(['error' => 'Deal is not disputed'], 400);
        }

        DB::transaction(function () use ($deal) {
            $deal->update(['status_id' => Status::DEAL_COMPLETED]);
            $this->transactionService->confirmDeal($deal->buyer, $deal->offer->seller, $deal->getAmount(), $deal);

            $chat = $this->chatService->createOrGetChat($deal->offer->seller->id, $deal->buyer_id);
            $this->messageService->sendDealNotification($deal, $chat, 'refundSeller');
        });
    }

    public function refundFiftyFifty(DisputeDealRequest $request)
    {
        $deal = Deal::findOrFail($request->deal_id);

        if ($deal->status_id !== Status::DEAL_DISPUTED) {
            return response()->json(['error' => 'Deal is not disputed'], 400);
        }

        DB::transaction(function () use ($deal) {
            $deal->buyer->decrement('frozen_balance', $deal->getAmount());
            $deal->buyer->increment('balance', $deal->getAmount() / 2);
            $deal->update(['status_id' => Status::DEAL_COMPLETED, 'price' => $deal->getAmount() / 2]);
            $this->transactionService->create($deal->buyer->id, $deal, 'deal_fifty');

            $deal->offer->seller->increment('balance', $deal->getAmount());
            $this->transactionService->create($deal->offer->seller->id, $deal, 'deal_fifty');

            $chat = $this->chatService->createOrGetChat($deal->offer->seller->id, $deal->buyer_id);
            $this->messageService->sendDealNotification($deal, $chat, 'refundFiftyFifty');
        });
    }

}
