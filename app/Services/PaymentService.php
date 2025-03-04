<?php

namespace App\Services;


use App\Models\Deposit;
use App\Models\Status;
use Illuminate\Http\Request;

class PaymentService
{
    public function __construct(private readonly TransactionService $transactionService)
    {
    }

    public function merchant(array $data, $depositId)
    {
        $merchantAccount = env('MERCHANT_ACCOUNT');
        $merchantDomain = 'domain.com';
        $orderReference = $depositId;
        $orderDate = time();
        $amount = $data['amount'];
        $currency = 'UAH';
        $productName = 'Пополнение баланса';
        $productCount = 1;
        $productPrice = $amount;


        $signatureString = implode(';', [
            $merchantAccount,
            $merchantDomain,
            $orderReference,
            $orderDate,
            $amount,
            $currency,
            $productName,
            $productCount,
            $productPrice,
        ]);

        $merchantSignature = hash_hmac('md5', $signatureString, env('MERCHANT_SECRET'));

        $paymentData = [
            'merchantAccount' => $merchantAccount,
            'merchantDomainName' => $merchantDomain,
            'merchantSignature' => $merchantSignature,
            'orderReference' => $orderReference,
            'orderDate' => $orderDate,
            'amount' => $amount,
            'currency' => $currency,
            'productName' => [$productName],
            'productCount' => [$productCount],
            'productPrice' => [$productPrice],
            'orderLifetime' => 600,
            'serviceUrl' => env('APP_URL') . '/api/payment/serviceUrl',
        ];

        return response()->json([
            'payment_data' => $paymentData,
            'payment_url' => 'https://secure.wayforpay.com/pay',
        ]);
    }

    public function service(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $orderReference = $data['orderReference'];
        $status = 'accept';
        $time = time();

        $signatureString = implode(';', [
            $orderReference,
            $status,
            $time,
        ]);


        $merchantSignature = hash_hmac('md5', $signatureString, env('MERCHANT_SECRET'));


        $deposit = Deposit::where('deposit_id', $data['orderReference'])->firstOrFail();
        switch ($data['transactionStatus']) {
            case 'Approved':
                if ($deposit->status_id !== Status::TRANSACTION_COMPLETED) {
                    $deposit->status_id = Status::TRANSACTION_COMPLETED;
                    $this->transactionService->incrementBalance($deposit->user, $deposit->amount);
                }
                break;
            case 'Pending':
            case 'InProcessing':
                $deposit->status_id = Status::TRANSACTION_PENDING;
                break;
            case 'Expired':
            case 'Declined':
                $deposit->status_id = Status::TRANSACTION_FAILED;
                break;
            case 'Refunded':
                if ($deposit->status_id !== Status::TRANSACTION_REFUNDED) {
                    $deposit->status_id = Status::TRANSACTION_REFUNDED;
                    $this->transactionService->decrementBalance($deposit->user, $deposit->amount);
                }
        }
        $deposit->save();

        return response()->json([
            'orderReference' => $orderReference,
            'status' => 'accept',
            'time' => $time,
            'signature' => $merchantSignature,
        ]);
    }
}
