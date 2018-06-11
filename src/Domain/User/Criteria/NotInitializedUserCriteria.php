<?php

namespace App\Domain\User\Criteria;

use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class NotInitializedUserCriteria implements DomainCriteria
{
    public function create(): Criteria
    {
        return Criteria::create()
            ->where(Criteria::expr()->isNull('initializedAt'))
            ->andWhere(Criteria::expr()->neq('email', null))
            ->orderBy(['createdAt' => Criteria::ASC]);
    }
}
