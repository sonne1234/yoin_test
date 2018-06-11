<?php

namespace App\Domain\Condo\Exception;

class UnitWithTheSameNumberAlreadyExistsInBuildingException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Unit with the same number already exists in condo building.');
    }
}
