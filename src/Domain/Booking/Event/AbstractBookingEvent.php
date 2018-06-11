<?php

namespace App\Domain\Booking\Event;

use App\Domain\Booking\Booking;
use App\Domain\DomainEvent;
use App\Domain\NotificationGateway\Message;
use App\Domain\NotificationGateway\NotificationInterface;

abstract class AbstractBookingEvent extends DomainEvent implements NotificationInterface
{
    /**
     * @var string
     */
    private $bookingId;

    /** @var array string */
    private $residentIds = [];

    /** @var Message */
    private $message;

    /** @var Booking */
    protected $booking;

    public function __construct(
        Booking $booking
    ) {
        $this->booking = $booking;
        $this->bookingId = $booking->getId();
        $this->message = $this->buildMessage($booking);
        if (!$booking->getIsCreatedByResident() && $booking->getResident()) {
            $this->residentIds[] = $booking->getResident()->getId();
        }
    }

    /**
     * @return string
     */
    public function getBookingId(): string
    {
        return $this->bookingId;
    }

    /**
     * @return string
     */
    public function bookingId(): string
    {
        return $this->bookingId;
    }

    /**
     * @return string
     */
    public function getMessageRecipientIds(): array
    {
        return $this->residentIds;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    abstract protected function buildMessage(Booking $booking);
}
