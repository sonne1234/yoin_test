<?php

namespace App\Domain\User\Exception;

class UserNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('User is not found.');
    }
}
