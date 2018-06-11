<?php

namespace App\Domain\User\Exception;

class UserWithTheSameEmailAlreadyExistsException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('You already have a user with the same e-mail.');
    }
}
