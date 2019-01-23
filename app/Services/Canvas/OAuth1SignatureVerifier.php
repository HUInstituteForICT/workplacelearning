<?php


namespace App\Services\Canvas;


use Illuminate\Http\Request;

class OAuth1SignatureVerifier
{

    /**
     * @var OAuth1SignatureBuilder
     */
    private $auth1SignatureBuilder;

    public function __construct(OAuth1SignatureBuilder $auth1SignatureBuilder)
    {
        $this->auth1SignatureBuilder = $auth1SignatureBuilder;
    }

    public function verifyRequest(Request $request): bool
    {
        return $this->verify($request->getMethod(), $request->getUri(), $request->all(), $request->get('oauth_signature'));
    }

    public function verify(string $method, string $url, array $data, string $receivedSignature): bool
    {
        $signature = $this->auth1SignatureBuilder->build($method, $url, $data);

        return $signature === $receivedSignature;
    }


}