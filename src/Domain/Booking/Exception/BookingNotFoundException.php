<?php

namespace App\Domain\Booking\Exception;

use Throwable;

class BookingNotFoundException extends \DomainException
{
    public function __construct($message = 'Booking is not found.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
