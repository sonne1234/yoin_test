<?php

namespace App\Domain\Booking\Event;

use App\Domain\Booking\Booking;
use App\Domain\NotificationGateway\Message;

class BookingDeclinedEvent extends AbstractBookingEvent
{
    protected function buildMessage(Booking $booking)
    {
        return new Message(Message::BOOKING_DECLINED, [$booking->getAmenity()->getName(), $booking->getBookingPeriod()]);
    }
}
