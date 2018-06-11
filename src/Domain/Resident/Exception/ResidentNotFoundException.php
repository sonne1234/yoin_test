<?php

namespace App\Domain\Resident\Exception;

class ResidentNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Resident is not found.');
    }
}
