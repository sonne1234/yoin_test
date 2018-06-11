<?php

namespace App\Domain\Unit\Exception;

class UnitCustomFieldWithTheSameNameExistsException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Unit custom field with the same name already exists in condo.');
    }
}
