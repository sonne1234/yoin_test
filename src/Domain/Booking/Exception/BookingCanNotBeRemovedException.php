<?php

namespace App\Domain\Booking\Exception;

class BookingCanNotBeRemovedException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Booking can be removed only if it has status approval_declined.');
    }
}
