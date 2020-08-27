<?php

declare(strict_types=1);

namespace App\Services\Canvas;

class OAuth1SignatureBuilder
{
    public function build(string $method, string $url, array $data): string
    {
        $preparedDataString = $this->prepareData($data);

        $signatureData = strtoupper($method).'&'.rawurlencode($url).'&'.rawurlencode($preparedDataString);

        $key = rawurlencode(config('canvas.secret')).'&';

        return base64_encode(hash_hmac('SHA1', $signatureData, $key, True));
    }

    private function prepareData($data): string
    {
        if (isset($data['oauth_signature'])) {
            unset($data['oauth_signature']);
        }

        ksort($data);

        $encodedData = array_map(function ($key, $value) {
            return rawurlencode($key).'='.rawurlencode($value);
        }, array_keys($data), $data);

        return implode('&', $encodedData);
    }
}
