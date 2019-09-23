<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CanvasLTIRequest;
use App\Services\Canvas\CanvasAuthenticator;
use App\Services\Canvas\OAuth1SignatureVerifier;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

class CanvasLTIController
{
    /**
     * @var CanvasAuthenticator
     */
    private $authenticator;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(CanvasAuthenticator $authenticator, LoggerInterface $logger)
    {
        $this->authenticator = $authenticator;
        $this->logger = $logger;
    }

    public function __invoke(CanvasLTIRequest $request, OAuth1SignatureVerifier $OAuth1SignatureVerifier)
    {
        if (!$OAuth1SignatureVerifier->verifyRequest($request)) {
            $this->logger->error('Oauth signature mismatch', [$request->toArray()]);
            throw new InvalidArgumentException('Invalid request, oauth_signature does not match ('.$request->get('oauth_signature').' vs. '.$OAuth1SignatureVerifier->signature.')');
        }

        // Roles is a string with commas separating each role
        $roles = $request->get('ext_roles');
        if (!str_contains($roles, 'urn:lti:instrole:ims/lis/Student')) {
            return view('auth.canvas.students-only');
        }

        $email = $request->get('lis_person_contact_email_primary');
        $canvasUserId = $request->get('user_id');
        $firstName = $request->get('lis_person_name_given');
        $lastName = $request->get('lis_person_name_family');

        return $this->authenticator->authenticate($email, $canvasUserId, $firstName, $lastName);
    }
}
