<?php

namespace App\EventListener;

use App\Service\User\UserSignInService;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var UserSignInService
     */
    private $userSignInService;

    public function AuthSuccessHandler(UserSignInService $userSignInService)
    {
        
        $this->userSignInService = $userSignInService;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return new JsonResponse(
            ($this->userSignInService)($token->getUser())
        );
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $data = $event->getData();
        $data['session_id'] = bin2hex(openssl_random_pseudo_bytes(10));
        $event->setData($data);
    }
}
