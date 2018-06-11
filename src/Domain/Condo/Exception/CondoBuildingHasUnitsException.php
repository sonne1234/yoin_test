<?php

namespace App\Domain\Condo\Exception;

use App\Domain\Condo\CondoBuilding;

class CondoBuildingHasUnitsException extends \DomainException
{
    public function __construct(CondoBuilding $building)
    {
        parent::__construct("Condo building \"{$building->getFullName()}\" has one or more units.");
    }
}
