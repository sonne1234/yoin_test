<?php

namespace App\Domain\Booking\Exception;

class BookingCanNotBeCancelledException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Booking can not be cancelled because it has "approval_declined" status or it has already ended.');
    }
}
