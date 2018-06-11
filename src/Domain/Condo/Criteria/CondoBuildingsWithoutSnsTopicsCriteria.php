<?php

namespace App\Domain\Condo\Criteria;

use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class CondoBuildingsWithoutSnsTopicsCriteria implements DomainCriteria
{
    public function __construct()
    {
    }

    public function create(): Criteria
    {
        return Criteria::create()
            ->where(Criteria::expr()->isNull('residentsTopic'))
            ->orWhere(Criteria::expr()->isNull('primeResidentsTopic'));
    }
}
