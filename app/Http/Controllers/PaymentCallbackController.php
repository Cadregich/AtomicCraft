<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        $sign = base64_encode(sha1($private_key . $data . $private_key, 1 ));

        $data = base64_decode($data);
        $filePath = storage_path('app/log.json');
        $content = json_encode(compact('sign', 'signature', 'data'));
        file_put_contents($filePath, $content);

        $data = json_decode($data);

        if ($sign === $signature) {
            $status = 'pass';

            \App\Models\Payment::create([
                'user_id' => session('user')['id'],
                'amount' => $data->amount,
                'currency' => $data->currency,
                'payment_system' => 'liqpay',
                'payment_id' => $data->payment_id
            ]);
        }

        return redirect('http://atomiccraft/cabinet?status=' . $status);
    }
}
