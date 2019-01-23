<?php


namespace App\Http\Controllers;


use App\Http\Requests\CanvasLTIRequest;
use App\Services\Canvas\CanvasAuthenticator;
use App\Services\Canvas\OAuth1SignatureVerifier;
use http\Exception\InvalidArgumentException;

class CanvasLTIController
{
    /**
     * @var CanvasAuthenticator
     */
    private $authenticator;

    public function __construct(CanvasAuthenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function __invoke(CanvasLTIRequest $request, OAuth1SignatureVerifier $OAuth1SignatureVerifier)
    {
        if (!$OAuth1SignatureVerifier->verifyRequest($request)) {
            throw new InvalidArgumentException('Invalid request, oauth_signature does not match');
        }

        $email = $request->get('lis_person_contact_email_primary');
        $canvasUserId = $request->get('user_id');
        $firstName = $request->get('lis_person_name_given');
        $lastName = $request->get('lis_person_name_family');

        return $this->authenticator->authenticate($email, $canvasUserId, $firstName, $lastName);
    }
}