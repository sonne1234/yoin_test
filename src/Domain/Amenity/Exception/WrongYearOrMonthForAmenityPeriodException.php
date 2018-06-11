<?php

namespace App\Domain\Amenity\Exception;

class WrongYearOrMonthForAmenityPeriodException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Wrong year or month exception.');
    }
}
