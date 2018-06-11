<?php

namespace App\Domain\Resident\Exception;

class ResidentCustomFieldNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Resident custom field is not found.');
    }
}
