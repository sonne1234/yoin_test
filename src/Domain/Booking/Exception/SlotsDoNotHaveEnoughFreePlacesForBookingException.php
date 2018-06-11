<?php

namespace App\Domain\Booking\Exception;

use Throwable;

class SlotsDoNotHaveEnoughFreePlacesForBookingException extends \DomainException
{
    public function __construct($message = 'One or more timeslots have less free places than you want to book.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
