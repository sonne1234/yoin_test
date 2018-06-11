<?php

namespace App\Domain\Booking\Exception;

class BookingCancellationReasonShouldBeProvidedException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Cancellation reason should be provided by admin.');
    }
}
