<?php

namespace App\Domain\Booking\Transformer;

use App\Domain\Amenity\AmenityTimeSlot;
use App\Domain\Amenity\Transformer\AmenityTimeSlotShortInfoTransformer;
use App\Domain\Amenity\Transformer\AmenityTransformer;
use App\Domain\DomainTransformer;

class MyResidentBookingTransformer extends DomainTransformer
{
    private $isIncludeAllSlots;

    public function __construct(bool $isIncludeAllSlots = false)
    {
        $this->isIncludeAllSlots = $isIncludeAllSlots;
    }

    /**
     * @param AmenityTimeSlot $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        $transformer = new AmenityTimeSlotShortInfoTransformer();

        $allSlots = [];
        if ($this->isIncludeAllSlots && $booking = $entity->getFirstBooking()) {
            foreach ($booking->getTimeSlots() as $ts) {
                $allSlots[] = $transformer->transform($ts);
            }
        }

        return [
            'slot' => $transformer->transform($entity),
            'booking' => (new BookingTransformer())->transform($entity->getFirstBooking()),
            'amenity' => $entity->getAmenity()
                ? (new AmenityTransformer())->transform($entity->getAmenity())
                : null,
        ]
            + ($allSlots ? ['allSlots' => $allSlots] : []);
    }
}
