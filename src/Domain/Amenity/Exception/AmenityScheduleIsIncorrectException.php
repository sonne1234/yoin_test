<?php

namespace App\Domain\Amenity\Exception;

use Throwable;

class AmenityScheduleIsIncorrectException extends \DomainException
{
    public function __construct($message = 'Amenity schedule is incorrect.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
