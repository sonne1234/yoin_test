<?php

namespace App\Domain\Booking\Exception;

use Throwable;

class WrongBookingRequestDataException extends \DomainException
{
    public function __construct($message = 'Wrong booking request data.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
