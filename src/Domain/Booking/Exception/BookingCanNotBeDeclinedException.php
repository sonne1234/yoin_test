<?php

namespace App\Domain\Booking\Exception;

class BookingCanNotBeDeclinedException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Booking can be declined only if it has status waiting_approval.');
    }
}
