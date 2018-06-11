<?php

namespace App\Domain\User\Exception;

class UserHasNotSetInitialPasswordYetException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('User has not set initial password yet.');
    }
}
