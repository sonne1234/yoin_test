<?php

namespace App\Domain\Account\Transformer;

use App\Domain\Account\Account;
use App\Domain\DomainTransformer;

class AccountTransformer extends DomainTransformer
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
            'logoUrl' => $entity->getAccountLogoUrl(),
            'createdAt' => $entity->getCreatedAt()->format(\DateTime::ATOM),
            'generalData' => $entity->getGeneralData(),
            'billingData' => $entity->getBillingData(),
            'statisticalData' => (new AccountOverallTransformer())->transform($entity),
            'primaryAccountAdmin' => ($admin = $entity->getPrimaryAccountAdmin())
                ? ($admin->getUserTransformer())->transform($admin)
                : null,
            'isAccountInfoFilled' => $entity->isAccountInfoFilled(),
            'isAccountGeneralInfoFilled' => $entity->isAccountGeneralInfoFilled(),
            'isAccountBillingInfoFilled' => $entity->isAccountBillingInfoFilled(),
        ];
    }
}
