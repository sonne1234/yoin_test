<?php

namespace App\Domain\Account\Criteria;

use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class AllAccountsCriteria implements DomainCriteria
{
    public function create(): Criteria
    {
        return Criteria::create()
            ->orderBy(['generalData.companyName' => Criteria::ASC]);
    }
}
