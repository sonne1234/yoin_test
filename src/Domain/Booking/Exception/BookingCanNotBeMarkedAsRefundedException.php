<?php

namespace App\Domain\Booking\Exception;

class BookingCanNotBeMarkedAsRefundedException extends \DomainException
{
    public function __construct()
    {
        parent::__construct(
            'Booking can not be marked as refunded because its status is not cancelled or it is not refundable or it is already refunded'
        );
    }
}
