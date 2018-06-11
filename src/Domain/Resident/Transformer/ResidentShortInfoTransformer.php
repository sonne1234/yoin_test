<?php

namespace App\Domain\Resident\Transformer;

use App\Domain\Resident\Resident;

class ResidentShortInfoTransformer extends ResidentTransformer
{
    /**
     * @param Resident $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return array_intersect_key(
            parent::transformOneEntity($entity),
            array_fill_keys([
                'type',
                'status',
                'isActive',
                'name',
                'image',
                'firstName',
                'lastName',
                'id',
                'homePhone',
                'cellPhone',
                'unitNumber',
                'condoBuilding',
                'unitId',
            ], null)
        );
    }
}
