<?php

namespace App\Domain\Amenity\Transformer;

use App\Domain\Amenity\AmenityTimeSlot;

class AmenityTimeSlotShortInfoTransformer extends AmenityTimeSlotTransformer
{
    public function __construct()
    {
        parent::__construct(true);
    }

    /**
     * @param AmenityTimeSlot $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return array_intersect_key(
            parent::transformOneEntity($entity),
            array_fill_keys(['id', 'type', 'date', 'timeFrom', 'timeTill', 'dayOfWeek', 'isOld'], null)
        );
    }
}
