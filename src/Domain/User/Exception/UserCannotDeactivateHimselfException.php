<?php

namespace App\Domain\User\Exception;

class UserCannotDeactivateHimselfException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('User can not deactivate himself.');
    }
}
