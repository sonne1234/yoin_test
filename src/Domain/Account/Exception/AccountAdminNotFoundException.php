<?php

namespace App\Domain\Account\Exception;

class AccountAdminNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Account admin is not found.');
    }
}
