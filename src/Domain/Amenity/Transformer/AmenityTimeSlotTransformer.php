<?php

namespace App\Domain\Amenity\Transformer;

use App\Domain\Amenity\AmenityTimeSlot;
use App\Domain\DomainTransformer;

class AmenityTimeSlotTransformer extends DomainTransformer
{
    /** @var bool */
    private $addDayOfWeek;

    /** @var bool */
    private $isClearBookedPlacesCount = true;

    public function __construct(bool $addDayOfWeek = false)
    {
        $this->addDayOfWeek = $addDayOfWeek;
    }

    public function setIsClearBookedPlacesCount(bool $isClearBookedPlacesCount): self
    {
        $this->isClearBookedPlacesCount = $isClearBookedPlacesCount;

        return $this;
    }

    /**
     * @param AmenityTimeSlot $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return array_merge(
            [
                'id' => $entity->getId(),
                'type' => $entity->getType(),
                'date' => $entity->getDate()->format(DATE_ATOM),
                'timeFrom' => $entity->getTimeFrom()
                    ? $entity->getTimeFrom()->format(DATE_ATOM)
                    : null,
                'timeTill' => $entity->getTimeTill()
                    ? $entity->getTimeTill()->format(DATE_ATOM)
                    : null,
                'freePlacesCount' => $entity->getIsBookedByAdministration()
                    ? null
                    : $entity->getFreePlacesCount(),
                'bookedPlacesCount' => $this->isClearBookedPlacesCount
                    ? null
                    : ($entity->getIsBookedByAdministration() ? null : $entity->getBookedPlacesCount()),
                'isBookedByAdministration' => $entity->getIsBookedByAdministration(),
                'isOld' => $entity->getIsOld(),
            ],
            $this->addDayOfWeek
                ? ['dayOfWeek' => $entity->getDayOfWeek()]
                : []
        );
    }
}
