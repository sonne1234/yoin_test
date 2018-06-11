<?php

namespace App\Domain\Condo\Transformer;

use App\Domain\Condo\Condo;
use App\Domain\DomainTransformer;

class CondoForResidentTransformer extends DomainTransformer
{
    /**
     * @param Condo $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'managingCompanyName' => $entity->getManagingCompanyName(),
            'managingCompanyAddress' => $entity->getManagingCompanyAddress(),
            'addressData' => array_intersect_key(
                (array) $entity->getGeneralData(),
                array_fill_keys(['streetName', 'zipCode', 'city', 'state', 'country', 'neighborhoodName'], null)
            ),
            'unitsCount' => $entity->getUnitsCount(),
            'description' => $entity->getDescription(),
            'buildings' => (new CondoBuildingTransformer())->transform($entity->getBuildings()),
            'createdAt' => $entity->getCreatedAt()->format(\DateTime::ATOM),
        ];
    }
}
