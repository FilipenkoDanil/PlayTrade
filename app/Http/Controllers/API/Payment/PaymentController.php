<?php

namespace App\Http\Controllers\API\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Deposit;
use App\Models\Status;
use App\Models\Transaction;
use App\Services\PaymentService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService, private readonly TransactionService $transactionService)
    {
    }

    public function payment(PaymentRequest $request)
    {
        $deposit = Deposit::create([
            'user_id' => Auth::id(),
            'deposit_id' => 'DP' . time(),
            'amount' => $request->amount,
            'status_id' => Status::TRANSACTION_PENDING,
        ]);

        $this->transactionService->create(Auth::id(), $deposit, Transaction::DEPOSIT);

        return $this->paymentService->merchant($request->validated(), $deposit->deposit_id);
    }

    public function service(Request $request)
    {
        return $this->paymentService->service($request);
    }
}
