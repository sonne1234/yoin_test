<?php

namespace App\Domain\Booking\Event;

use App\Domain\Booking\Booking;
use App\Domain\NotificationGateway\Message;

class BookingApprovedEvent extends AbstractBookingEvent
{
    protected function buildMessage(Booking $booking)
    {
        return new Message(Message::BOOKING_APPROVED, [$booking->getAmenity()->getName(), $booking->getBookingPeriod()]);
    }
}
