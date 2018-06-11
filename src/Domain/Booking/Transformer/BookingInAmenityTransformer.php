<?php

namespace App\Domain\Booking\Transformer;

use App\Domain\Booking\Booking;

class BookingInAmenityTransformer extends BookingInTimeslotTransformer
{
    /**
     * @param Booking $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        $this->isShowSlots = false;

        return parent::transformOneEntity($entity);
    }
}
