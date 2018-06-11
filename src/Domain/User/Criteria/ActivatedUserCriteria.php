<?php

namespace App\Domain\User\Criteria;

use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class ActivatedUserCriteria implements DomainCriteria
{
    public function create(): Criteria
    {
        return Criteria::create()
            ->where(Criteria::expr()->neq('initializedAt', null))
            ->andWhere(Criteria::expr()->eq('isActive', true))
            ->orderBy(['lastName' => Criteria::ASC]);
    }
}
