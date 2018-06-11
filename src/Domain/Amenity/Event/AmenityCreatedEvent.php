<?php

namespace App\Domain\Amenity\Event;

use App\Domain\Amenity\Amenity;
use App\Domain\DomainEvent;

class AmenityCreatedEvent extends DomainEvent
{
    /** @var  string */
    private $amenityId;

    public function __construct(Amenity $amenity)
    {
        $this->amenityId = $amenity->getId();
    }

    public function amenityId(): string
    {
        return $this->amenityId;
    }
}
