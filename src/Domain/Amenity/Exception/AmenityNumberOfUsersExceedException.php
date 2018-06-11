<?php

namespace App\Domain\Amenity\Exception;

class AmenityNumberOfUsersExceedException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Amenity number of users can not be greater than capacity.');
    }
}
