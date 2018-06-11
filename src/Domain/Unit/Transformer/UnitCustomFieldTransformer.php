<?php

namespace App\Domain\Unit\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\Unit\UnitCustomField;

class UnitCustomFieldTransformer extends DomainTransformer
{
    /**
     * @param UnitCustomField $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return $entity->toArray();
    }
}
