<?php

namespace App\Domain\Resident\Exception;

use App\Domain\Resident\ResidentCustomFieldValue;

class ResidentCustomFieldIsRequiredException extends \DomainException
{
    public function __construct(ResidentCustomFieldValue $value)
    {
        parent::__construct("Custom field \"{$value->getCustomFieldName()}\" is required.");
    }
}
