<?php

namespace App\Domain\Resident\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\Resident\ResidentCustomField;

class ResidentCustomFieldTransformer extends DomainTransformer
{
    /**
     * @param ResidentCustomField $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return $entity->toArray();
    }
}
