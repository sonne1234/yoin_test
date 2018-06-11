<?php

namespace App\Domain\Booking\Event;

use App\Domain\Booking\Booking;
use App\Domain\NotificationGateway\Message;
use App\Domain\PlatformNotification\AbstractNotification;
use App\Domain\PlatformNotification\NotificationDispatchable;
use App\Domain\PlatformNotification\Type\NewBookingWFARequestNotification;

class BookingStatusChangedToWaitingApprovalEvent extends AbstractBookingEvent implements NotificationDispatchable
{
    protected function buildMessage(Booking $booking)
    {
        return new Message(Message::BOOKING_WAITING_FOR_PAYMENT, [$booking->getAmenity()->getName(), $booking->getBookingPeriod()]);
    }

    public function getPlatformNotification(): AbstractNotification
    {
        return (new NewBookingWFARequestNotification())
            ->setAuthor($this->getCurrentUser())
            ->setTargetEntityId($this->booking->getAmenity()->getId())
            ->setCondo($this->booking->getAmenity()->getCondo())
            ->setMessageArgs([$this->booking->getAmenity()->getName()]);
    }
}
