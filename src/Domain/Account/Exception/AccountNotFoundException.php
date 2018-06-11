<?php

namespace App\Domain\Account\Exception;

class AccountNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Account is not found.');
    }
}
