<?php

namespace App\Services;

use App\Models\Deal;
use App\Models\Offer;
use App\Models\Status;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DealService
{
    public function __construct(
        private readonly TransactionService $transactionService,
        private readonly DealValidator      $dealValidator,
    )
    {
    }

    public function create(array $data): Deal|string
    {
        $offer = Offer::findOrFail($data['offer_id']);
        $buyer = Auth::user();

        $validationError = $this->dealValidator->validateCreation($offer, $data['quantity']);

        if ($validationError) {
            return $validationError;
        }

        return DB::transaction(function () use ($offer, $buyer, $data) {
            $deal = Deal::create([
                'quantity' => $data['quantity'],
                'price' => $offer->price * $data['quantity'],
                'buyer_id' => $buyer->id,
                'offer_id' => $offer->id,
                'offer_title' => $offer->title,
                'offer_description' => $offer->description,
                'offer_attributes' => $offer->attributes,
                'offer_server' => $offer->server,
                'offer_game' => $offer->category->game->title,
                'offer_category' => $offer->category->title,
                'offer_unit' => $offer->category->unit->title,
                'status_id' => Status::DEAL_IN_PROGRESS,
            ]);

            $offer->decrement('amount', $deal->quantity);

            if ($offer->amount == 0) {
                $offer->is_active = false;
                $offer->save();
            }

            $this->transactionService->create($buyer->id, $deal, Transaction::DEAL_PURCHASE);
            $this->transactionService->freezeBalance($buyer, $deal->price);

            return $deal;
        });
    }

    public function confirm(Deal $deal): void
    {
        DB::transaction(function () use ($deal) {
            $deal->update(['status_id' => Status::DEAL_COMPLETED]);

            $this->transactionService->confirmDeal($deal->buyer, $deal->offer->seller, $deal->price, $deal);
        });
    }

    public function cancel(Deal $deal): void
    {
        DB::transaction(function () use ($deal) {
            $deal->update(['status_id' => Status::DEAL_CANCELED]);

            $this->transactionService->create($deal->buyer->id, $deal, Transaction::DEAL_CANCELED);
            $this->transactionService->unfreezeBalance($deal->buyer, $deal->price);
        });
    }

    public function dispute(Deal $deal): void
    {
        DB::transaction(function () use ($deal) {
            $deal->update(['status_id' => Status::DEAL_DISPUTED]);
        });
    }
}
