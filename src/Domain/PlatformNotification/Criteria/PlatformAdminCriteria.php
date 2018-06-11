<?php

namespace App\Domain\PlatformNotification\Criteria;

use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class PlatformAdminCriteria implements DomainCriteria
{
    public function create(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('isActive', true))
            ->andWhere(Criteria::expr()->eq('isNotificationsEnabled', true));
    }
}
