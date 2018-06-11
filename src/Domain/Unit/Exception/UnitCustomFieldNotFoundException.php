<?php

namespace App\Domain\Unit\Exception;

class UnitCustomFieldNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Unit custom field is not found.');
    }
}
