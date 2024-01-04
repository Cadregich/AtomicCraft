<?php

namespace App\Services;

class PaymentFinishedService
{
    private Coins $coins;

    public function __construct(Coins $coins)
    {
        $this->coins = $coins;
    }

    public function updateUserBalance($user_id, $amountCoins, $currency)
    {
        $replenishableBalance = $this->coins->currencyToCoins($amountCoins, $currency);
        $depositBonuses = config('bonuses.deposit');
        $replenishableBalanceWithBonus = $replenishableBalance + $this->getBonusFromReplenishableBalance($replenishableBalance, $depositBonuses);
        \App\Models\User::find($user_id)->increment('balance', $replenishableBalanceWithBonus);
    }

    public function addPaymentRecord($orderData)
    {
        $payment = \App\Models\Payment::where('order_id', $orderData->order_id)->firstOrFail();

        $payment->update([
            'amount' => $orderData->amount,
            'currency' => $orderData->currency,
            'payment_id' => $orderData->payment_id,
            'payment_complete' => true,
        ]);
        return $payment;
    }

    private function getBonusFromReplenishableBalance($replenishableBalance, $depositBonuses)
    {
        $bonusFactor = 1;

        foreach ($depositBonuses as $key => $value) {
            if ($replenishableBalance >= $key) {
                $bonusFactor = 1 + (intval($value) / 100);
            }
        }
        return $replenishableBalance * $bonusFactor - $replenishableBalance;
    }
}
