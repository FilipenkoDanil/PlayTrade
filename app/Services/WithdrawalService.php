<?php

namespace App\Services;

use App\Models\Status;
use App\Models\Withdrawal;

class WithdrawalService
{
    public function createWithdrawal($data, $userId)
    {
        $feePercentage = 5;

        return Withdrawal::create([
            'user_id' => $userId,
            'card_number' => $data['card_number'],
            'requested_amount' => $data['requested_amount'],
            'payout_amount' => $data['requested_amount'] - ($data['requested_amount'] * ($feePercentage / 100)),
            'status_id' => Status::TRANSACTION_PENDING
        ]);
    }
}
