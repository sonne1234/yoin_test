<?php

namespace App\Domain\User\Exception;

class UserCannotSetPreviousUsedPasswordException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('New password can not be the same as the old one.');
    }
}
