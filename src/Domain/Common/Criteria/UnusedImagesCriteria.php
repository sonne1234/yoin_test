<?php

namespace App\Domain\Common\Criteria;

use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class UnusedImagesCriteria implements DomainCriteria
{
    const MAX_LIVING_TIME = 1; // days

    public function create(): Criteria
    {
        return Criteria::create()->where(
            Criteria::expr()->andX(
                Criteria::expr()->eq('isUsed', false),
                Criteria::expr()->lt('updatedAt', (new \DateTime())->modify('-'.self::MAX_LIVING_TIME.' days'))
            )
        );
    }
}
