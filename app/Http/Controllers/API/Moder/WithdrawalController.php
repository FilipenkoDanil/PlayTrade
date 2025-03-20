<?php

namespace App\Http\Controllers\API\Moder;

use App\Http\Controllers\Controller;
use App\Http\Resources\WithdrawalResource;
use App\Models\Status;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index()
    {
        return WithdrawalResource::collection(Withdrawal::with('user')->where('status_id', Status::TRANSACTION_PENDING)->get())->resolve();
    }

    public function approve(Withdrawal $withdrawal)
    {
        DB::transaction(function () use ($withdrawal) {
            $withdrawal->update(['status_id' => Status::TRANSACTION_COMPLETED]);
            $withdrawal->user()->decrement('frozen_balance', $withdrawal->requested_amount);
        });
    }
}
