<?php

namespace App\Domain\Account\Transformer;

use App\Domain\Account\Account;
use App\Domain\DomainTransformer;

class AccountOnlyNameTransformer extends DomainTransformer
{
    /**
     * @param Account $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'companyName' => $entity->getAccountCompanyName(),
        ];
    }
}
