<?php

namespace App\Domain\Condo\Exception;

class CondoHasNotPaymentAvailableException extends \DomainException
{
    public function __construct()
    {
        parent::__construct("Condo doesn't have payment available.");
    }
}
