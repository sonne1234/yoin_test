<?php

namespace App\Domain\Condo\Exception;

class CondoAdminNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Condo admin is not found.');
    }
}
