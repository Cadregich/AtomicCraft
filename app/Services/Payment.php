<?php

namespace App\Services;

use App\Services\PaymentSystems\Liqpay;
use Carbon\Carbon;

class Payment
{
    public array $availableCurrencies = ['UAH', 'EUR', 'USD'];
    private int $amount;
    private string $currency;
    private string $paymentDescription = 'Пополнение монет AtomicCraft';


    public function __construct(int $amount, string $currency)
    {
        if ($amount <= 0 || $amount > 999999) {
            throw new \InvalidArgumentException('Invalid amount');
        }

        if (!in_array($currency, $this->availableCurrencies)) {
            throw new \InvalidArgumentException('Invalid currency');
        }

        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function LiqPay()
    {
        $public_key = env('LIQPAY_PUBLIC_KEY');
        $private_key = env('LIQPAY_PRIVATE_KEY');
        $paymentData = [
            'action' => 'pay',
            'amount' => $this->amount,
            'currency' => $this->currency,
            'description' => $this->paymentDescription,
            'order_id' => $this->generateRandomId(),
            'version' => '3',
            'public_key' => $public_key,
            'private_key' => $private_key,
            'server_url' => 'http://atomiccraft/api/callback/liqpay',
            'result_url' => 'http://atomiccraft/api/cabinet/liqpay',
        ];
        $liqpay = new Liqpay($public_key, $private_key);
        return $liqpay->getApiParams($paymentData);
    }

    private function generateRandomId($length = 30)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        $date = Carbon::now()->format('dmy');

        for ($i = 0; $i < $length - 6; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $date . $randomString;
    }
}
