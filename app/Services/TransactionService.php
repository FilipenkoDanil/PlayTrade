<?php

namespace App\Services;

use App\Contracts\Transactable;
use App\Models\Transaction;
use App\Models\User;


class TransactionService
{
    public function create(int $userId, Transactable $transactable, string $type): Transaction
    {
        return Transaction::create([
            'user_id' => $userId,
            'amount' => $transactable->getSignedAmount($type),
            'transactable_type' => $transactable->getMorphClass(),
            'transactable_id' => $transactable->getKey(),
        ]);
    }

    public function freezeBalance(User $user, float $amount): void
    {
        $user->decrement('balance', $amount);
        $user->increment('frozen_balance', $amount);
    }

    public function unfreezeBalance(User $user, float $amount): void
    {
        $user->increment('balance', $amount);
        $user->decrement('frozen_balance', $amount);
    }

    public function confirmDeal(User $buyer, User $seller, float $amount, Transactable $deal): void
    {
        $buyer->decrement('frozen_balance', $amount);
        $seller->increment('balance', $amount);

        $this->create($seller->id, $deal, Transaction::DEAL_SALE);
    }
}
