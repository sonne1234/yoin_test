<?php

namespace App\Domain\Account\Exception;

class DeactivatePrimaryAccountAdminException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Primary account admin can not be deactivated.');
    }
}
