<?php

namespace App\Services\PaymentSystems;

class Liqpay
{
    private string $public_key;
    private string $private_key;
    protected array $supportedCurrencies = ['EUR', 'USD', 'UAH'];
    private string $checkout_url = 'https://www.liqpay.ua/api/3/checkout';
    protected array $actions = ["pay", "hold", "subscribe", "paydonate"];

    public function __construct($public_key, $private_key)
    {
        if (empty($public_key)) {
            throw new \InvalidArgumentException('public_key is empty');
        }

        if (empty($private_key)) {
            throw new \InvalidArgumentException('private_key is empty');
        }

        $this->public_key = $public_key;
        $this->private_key = $private_key;
    }

    public function getApiParams($params): array
    {
        $params = $this->checkParams($params);
        $params['public_key'] = $this->public_key;
        return [
            'url' => $this->checkout_url,
            'data' => $this->encodeParams($params),
            'signature' => $this->getSignature($params)
        ];
    }

    public function getSignature($params): string
    {
        $private_key = $this->private_key;

        $json = $this->encodeParams($params);
        return $this->calculateHashAndEncodeStr($private_key . $json . $private_key);
    }

    public function calculateHashAndEncodeStr($str): string
    {
        return base64_encode(sha1($str, 1));
    }

    private function checkParams($params)
    {
        $requiredFields = ['version', 'amount', 'currency', 'action', 'order_id', 'description'];

        foreach ($requiredFields as $field) {
            if (!isset($params[$field])) {
                throw new \InvalidArgumentException("$field is null");
            }

            if ($field === 'currency' && !in_array($params['currency'], $this->supportedCurrencies)) {
                throw new \InvalidArgumentException('currency is not supported');
            }

            if ($field === 'action' && !in_array($params['action'], $this->actions)) {
                throw new \InvalidArgumentException('action is not supported');
            }
        }

        return $params;
    }

    private function encodeParams($params): string
    {
        return base64_encode(json_encode($params));
    }
}
