<?php

namespace App\Domain\Amenity\Exception;

class AmenityNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Amenity is not found.');
    }
}
