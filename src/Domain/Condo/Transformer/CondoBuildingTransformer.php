<?php

namespace App\Domain\Condo\Transformer;

use App\Domain\Condo\CondoBuilding;
use App\Domain\DomainTransformer;

class CondoBuildingTransformer extends DomainTransformer
{
    /**
     * @param CondoBuilding $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'number' => $entity->getNumber(),
        ];
    }
}
