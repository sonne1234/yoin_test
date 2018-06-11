<?php

namespace App\Domain\Booking\Exception;

class BookingCanNotBeApprovedException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Booking can be approved only if it has status waiting_approval.');
    }
}
