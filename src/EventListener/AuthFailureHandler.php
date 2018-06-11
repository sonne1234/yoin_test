<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class AuthFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = 'Bad credentials.';

        if ($exception instanceof BadCredentialsException) {
            $message = 'Incorrect login or password or both.';
        } elseif ($exception instanceof DisabledException) {
            $message = 'User\'s account is disabled.';
        }

        return new JsonResponse(
            ['code' => 401, 'message' => $message],
            401
        );
    }
}
