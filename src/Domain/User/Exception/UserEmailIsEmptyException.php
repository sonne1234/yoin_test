<?php

namespace App\Domain\User\Exception;

class UserEmailIsEmptyException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('E-mail can not be empty for this user.');
    }
}
