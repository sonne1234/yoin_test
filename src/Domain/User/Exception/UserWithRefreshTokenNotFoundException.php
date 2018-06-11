<?php

namespace App\Domain\User\Exception;

class UserWithRefreshTokenNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('User with a given refresh token is not found.');
    }
}
