<?php

namespace App\Domain\Booking\Exception;

class BookingCanNotBeMarkedAsPaidException extends \DomainException
{
    public function __construct()
    {
        parent::__construct(
            'Booking can be pay only if it has status waiting_payment.'
        );
    }
}
