<?php

namespace App\Domain\Amenity\Exception;

class AmenityWithTheSameNameAlreadyExistsInBuildingException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Amenity with the same name already exists in condo building.');
    }
}
