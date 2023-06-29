<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

class PaymentService
{
    private $ratesRelationUAH;
    private $ratesRelationCoins = [];
    private $factor = 2.4;
    private $availableCurrencies = ['RUB', 'USD', 'UAH'];

    /**
     * @throws Exception
     */

    public function __construct()
    {
        $this->ratesRelationUAH = $this->getCurrencyRates();

        foreach ($this->ratesRelationUAH as $currency => $value) {
            $this->ratesRelationCoins[$currency] = $value * $this->factor;
        }
    }

    /**
     * Converts a given count of currency to coins based.
     *
     * @throws Exception
     */

    public function currencyToCoins(int $count, string $currency)
    {
        if ($count <= 0) {
            throw new InvalidArgumentException('Invalid count');
        }

        if (!in_array($currency, $this->availableCurrencies)) {
            throw new InvalidArgumentException('Invalid currency');
        }

        foreach ($this->ratesRelationCoins as $coin => $rate) {
            if ($currency === $coin) {
                return $count * $rate;
            }
        }

        throw new Exception('Unknown error');
    }

    /**
     * Converts a given count of coins to currency based on the selected currency.
     *
     * @throws Exception
     */

    public function coinsToCurrency(int $count, string $currency): float
    {
        if ($count <= 0) {
            throw new InvalidArgumentException('Invalid count');
        }

        if (!in_array($currency, $this->availableCurrencies)) {
            throw new InvalidArgumentException('Invalid currency');
        }

        foreach ($this->ratesRelationCoins as $coin => $rate) {
            if ($currency === $coin) {
                return round($count / $rate, 2);
            }
        }

        throw new Exception('Unknown error');
    }

    /**
     * Gets currency exchange rates in relation to UAH from a remote API.
     *
     * @throws Exception
     */

    private function getCurrencyRates(): array
    {
        $client = new Client();
        try {
            $response = $client->get('https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?json');
        } catch (GuzzleException $e) {
            throw new Exception('Error while getting data: ' . $e->getMessage());
        }

        $data = json_decode($response->getBody(), true);
        if ($data === null) {
            throw new Exception('Error while decoding JSON');
        }

        $currencies = array_fill_keys($this->availableCurrencies, 0);

        foreach ($data as $item) {
            if (in_array($item['cc'], $this->availableCurrencies)) {
                $currencies[$item['cc']] = $item['rate'];
            }
        }

        if (isset($currencies['UAH'])) {
            $currencies['UAH'] = 1;
        }

        return $currencies;
    }
}
