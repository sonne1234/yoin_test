<?php

namespace App\Domain\Resident\Exception;

class ResidentCustomFieldWithTheSameNameExistsException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Resident custom field with the same name already exists in condo.');
    }
}
