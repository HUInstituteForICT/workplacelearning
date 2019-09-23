<?php

declare(strict_types=1);

namespace App\Services\Canvas;

use Illuminate\Http\Request;

class OAuth1SignatureVerifier
{
    /**
     * @var OAuth1SignatureBuilder
     */
    private $auth1SignatureBuilder;

    /** @var string|null */
    public $signature;

    public function __construct(OAuth1SignatureBuilder $auth1SignatureBuilder)
    {
        $this->auth1SignatureBuilder = $auth1SignatureBuilder;
    }

    public function verifyRequest(Request $request): bool
    {
        return $this->verify($request->method(), $this->getUrlWithCorrectScheme($request->url()), $request->all(),
            $request->get('oauth_signature'));
    }

    private function getUrlWithCorrectScheme(string $url): string
    {
        if (str_contains($url, 'localhost')) {
            return $url;
        }

        return str_replace('http://', 'https://', $url);
    }

    public function verify(string $method, string $url, array $data, string $receivedSignature): bool
    {
        $this->signature = $this->auth1SignatureBuilder->build($method, $url, $data);

        return $this->signature === $receivedSignature;
    }
}
