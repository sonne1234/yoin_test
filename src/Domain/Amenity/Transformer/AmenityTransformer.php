<?php

namespace App\Domain\Amenity\Transformer;

use App\Domain\Amenity\Amenity;
use App\Domain\DomainTransformer;

class AmenityTransformer extends DomainTransformer
{
    /** @var  \Closure */
    private $calculateCountOfWaitingApprovalBookings;

    public function setCalculateCountOfWaitingApprovalBookings(\Closure $closure): self
    {
        $this->calculateCountOfWaitingApprovalBookings = $closure;

        return $this;
    }

    /**
     * @param Amenity $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'isObservable' => $entity->getIsObservable(),
            'generalData' => $entity->getGeneralData(),
            'availabilityData' => $entity->getAvailabilityData(),
            'bookingData' => $entity->getBookingData(),
            'countOfWaitingApprovalBookings' =>
                $this->calculateCountOfWaitingApprovalBookings
                    ? ($this->calculateCountOfWaitingApprovalBookings)($entity)
                    : null,
        ];
    }
}
