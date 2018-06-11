<?php

namespace App\Domain\Unit\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\Unit\Unit;

class UnitShortInfoTransformer extends DomainTransformer
{
    /**
     * @param Unit $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'number' => $entity->getNumber(),
        ];
    }
}
