<?php

namespace App\Domain\Unit\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\Unit\Pet;

class PetTransformer extends DomainTransformer
{
    /**
     * @param Pet $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return $entity->toArray();
    }
}
