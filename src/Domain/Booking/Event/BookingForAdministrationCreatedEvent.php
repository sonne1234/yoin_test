<?php

namespace App\Domain\Booking\Event;

use App\Domain\DomainEvent;

class BookingForAdministrationCreatedEvent extends DomainEvent
{
    /**
     * @var string
     */
    private $amenityId;

    /**
     * @var bool
     */
    private $isNotifyResidents;

    public function __construct(
        string $amenityId,
        bool $isNotifyResidents
    ) {
        $this->amenityId = $amenityId;
        $this->isNotifyResidents = $isNotifyResidents;
    }

    public function amenityId(): string
    {
        return $this->amenityId;
    }

    public function isNotifyResidents(): bool
    {
        return $this->isNotifyResidents;
    }
}
