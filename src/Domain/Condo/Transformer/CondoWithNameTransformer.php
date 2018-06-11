<?php

namespace App\Domain\Condo\Transformer;

use App\Domain\Condo\Condo;
use App\Domain\DomainTransformer;

class CondoWithNameTransformer extends DomainTransformer
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
            'accountId' => $entity->getAccount()
                ? $entity->getAccount()->getId()
                : null,
            'name' => $entity->getName(),
        ];
    }
}
