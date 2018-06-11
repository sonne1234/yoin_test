<?php

namespace App\Domain\Unit\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\Unit\Unit;

class UnitForResidentTransformer extends DomainTransformer
{
    /**
     * @param Unit $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return $entity->toArray(true);
    }
}
