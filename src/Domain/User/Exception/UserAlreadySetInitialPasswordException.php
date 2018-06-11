<?php

namespace App\Domain\User\Exception;

class UserAlreadySetInitialPasswordException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('User has already set initial password.');
    }
}
