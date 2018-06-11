<?php

namespace App\Domain\User\Exception;

class UserCannotActivateHimselfException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('User can not activate himself.');
    }
}
