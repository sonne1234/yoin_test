<?php

namespace App\Domain\Booking\Transformer;

use App\Domain\Amenity\Transformer\AmenityTimeSlotShortInfoTransformer;
use App\Domain\Booking\Booking;
use App\Domain\DomainTransformer;
use App\Domain\Resident\Transformer\ResidentTransformer;

class BookingInTimeslotTransformer extends DomainTransformer
{
    protected $isShowResidentNumber = true;
    protected $isShowSlots = true;

    public function setIsShowResidentNumber(bool $isShowResidentNumber): self
    {
        $this->isShowResidentNumber = $isShowResidentNumber;

        return $this;
    }

    /**
     * @param Booking $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        $numberFields = [
            'phone',
            'homePhone',
            'cellPhone',
        ];

        $res = [
            'booking' => (new BookingTransformer())->transform($entity),
            'resident' => array_intersect_key(
                (new ResidentTransformer(false, true))->transform(
                    $entity->getResident()
                ),
                array_fill_keys(array_merge([
                    'name',
                    'id',
                    'unitNumber',
                    'unitId',
                    'condoBuilding',
                    'firstName',
                    'lastName',
                    'image',
                ], $numberFields), null)
            ),
        ];

        if ($this->isShowSlots) {
            $res['slots'] = (new AmenityTimeSlotShortInfoTransformer())->transform($entity->getTimeSlots());
        }

        foreach ($numberFields as $field) {
            $res['resident'][$field] = $this->isShowResidentNumber
                ? $res['resident'][$field]
                : '';
        }

        return $res;
    }
}
