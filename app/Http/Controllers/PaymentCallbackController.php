<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/*
 * Processes callbacks after making a payment from payment systems
 */

class PaymentCallbackController extends Controller
{
    public function Liqpay(Request $request) {
        $status = 'fail';
        $data = $request->input('data');
        $signature = $request->input('signature');
        $private_key = env('LIQPAY_PRIVATE_KEY');
        $expected_signature = base64_encode(sha1($private_key . $data . $private_key, 1 ));

        if ($expected_signature === $signature) {
            $status = 'pass';
            $data = json_decode(base64_decode($data));
            try {
                \App\Models\Payment::where('order_id', $data->order_id)->update([
                    'amount' => $data->amount,
                    'currency' => $data->currency,
                    'payment_id' => $data->payment_id,
                    'payment_complete' => true,
                ]);
            } catch (\Exception $e) {
                Log::channel('payment')->info('Ошибка создания платежа ' . $data->payment_id .
                    ' | Ошибка: ' . $e->getMessage());
            }
        }

        return redirect('http://atomiccraft/cabinet?status=' . $status);
    }
}
