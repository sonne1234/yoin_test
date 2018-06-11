<?php

namespace App\Domain\Unit\Exception;

use App\Domain\Unit\UnitCustomFieldValue;

class UnitCustomFieldIsRequiredException extends \DomainException
{
    public function __construct(UnitCustomFieldValue $value)
    {
        parent::__construct("Custom field \"{$value->getCustomFieldName()}\" is required.");
    }
}
