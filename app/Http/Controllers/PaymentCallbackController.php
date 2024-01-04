<?php

namespace App\Http\Controllers;

use App\Services\PaymentFinishedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/*
 * Processes callbacks after making a payment from payment systems
 */

class PaymentCallbackController extends Controller
{
    private PaymentFinishedService $paymentFinishedService;

    public function __construct(PaymentFinishedService $paymentFinishedService)
    {
        $this->paymentFinishedService = $paymentFinishedService;
    }

    public function Liqpay(Request $request) {
        $status = 'fail';
        $paymentData = $request->input('data');
        $signature = $request->input('signature');
        $private_key = env('LIQPAY_PRIVATE_KEY');
        $expected_signature = base64_encode(sha1($private_key . $paymentData . $private_key, 1 ));

        if ($expected_signature === $signature) {
            $status = 'pass';
            $paymentData = json_decode(base64_decode($paymentData));
            try {
                $payment = $this->paymentFinishedService->addPaymentRecord($paymentData);
                $user_id = $payment->user_id;

                DB::transaction(function () use ($user_id, $paymentData) {
                    $this->paymentFinishedService->updateUserBalance($user_id, $paymentData->amount, $paymentData->currency);
                });
            } catch (\Exception $e) {
                Log::channel('payment')->info('Ошибка создания платежа ' . $paymentData->payment_id .
                    ' | Ошибка: ' . $e->getMessage());
            }
        }

        return redirect('http://atomiccraft/cabinet?status=' . $status);
    }
}
