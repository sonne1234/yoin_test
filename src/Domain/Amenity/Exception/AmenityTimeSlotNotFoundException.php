<?php

namespace App\Domain\Amenity\Exception;

class AmenityTimeSlotNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Amenity timeslot is not found.');
    }
}
