<?php

namespace App\Domain\Booking\Exception;

use Throwable;

class SlotsUnavailableForBookingException extends \DomainException
{
    public function __construct($message = 'One or more timeslots are unavailable for booking.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
