<?php

namespace App\Domain\Invoice\Criteria;

use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class OverdueMaintenanceFeeInvoiceCriteria implements DomainCriteria
{
    public function create(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('isPaid', false))
            ->andWhere(Criteria::expr()->lte(
                'payPeriod',
                (new \DateTime())
                    ->setTime(0, 0, 0)
                    ->setDate(
                        (new \DateTime())->format('Y'),
                        (new \DateTime())->format('m'),
                        1
                    )
                    ->modify('-1 month')->modify('-1 month')
            ));
    }
}
