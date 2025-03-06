<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Http\Requests\StoreWithdrawalRequest;
use App\Http\Requests\UpdateWithdrawalRequest;
use App\Services\TransactionService;
use App\Services\WithdrawalService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function __construct(private readonly WithdrawalService $withdrawalService, private readonly TransactionService $transactionService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWithdrawalRequest $request)
    {
        $user = Auth::user();

        if ($user->balance < $request->requested_amount) {
            return response()->json(['message' => "You don't have sufficient balance"], 403);
        }

        return DB::transaction(function () use ($request, $user) {
            $withdrawal = $this->withdrawalService->createWithdrawal($request->validated(), $user->id);
            $this->transactionService->create($user->id, $withdrawal, Transaction::WITHDRAWAL);
            $this->transactionService->freezeBalance($user, $withdrawal->requested_amount);

            return $withdrawal;
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWithdrawalRequest $request, Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Withdrawal $withdrawal)
    {
        //
    }

    public function cancel(Withdrawal $withdrawal)
    {
        $user = Auth::user();

        if ($user->id !== $withdrawal->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($withdrawal->status_id !== Status::TRANSACTION_PENDING) {
            return response()->json(['message' => 'Withdrawal cannot be canceled'], 403);
        }

        DB::transaction(function () use ($withdrawal, $user) {
            $withdrawal->status_id = Status::TRANSACTION_CANCELED;
            $this->transactionService->unfreezeBalance($user, $withdrawal->getAmount());
            $withdrawal->save();

            return $withdrawal;
        });
    }
}
