<?php

namespace App\Domain\Account\Transformer;

use App\Domain\Account\Account;
use App\Domain\Condo\Condo;
use App\Domain\DomainTransformer;
use App\Domain\Unit\Unit;

class AccountOverallTransformer extends DomainTransformer
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
            'feePerMonth' => 0.00,
            'adminsCount' => 0,
            'staffCount' => 0,
            'pagamobilTransactions' => 0.00,
            'zolversTransactions' => 0.00,
            'monthlyRevenue' => 0.00,
        ] + (new AccountStatisticsTransformer())->transform($entity);
    }
}
