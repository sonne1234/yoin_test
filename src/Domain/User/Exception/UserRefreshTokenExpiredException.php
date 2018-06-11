<?php

namespace App\Domain\User\Exception;

class UserRefreshTokenExpiredException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('User\'s refresh token has been expired.');
    }
}
