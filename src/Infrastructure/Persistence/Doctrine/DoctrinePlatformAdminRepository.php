<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Platform\PlatformAdmin;
use App\Domain\Platform\PlatformAdminRepository;

class DoctrinePlatformAdminRepository extends AbstractDoctrineRepository implements PlatformAdminRepository
{
    protected function repositoryClassName(): string
    {
        return PlatformAdmin::class;
    }

    public function getActivePlatformAdminsCount(): int
    {
        return $this
            ->createQueryBuilder('pa')
            ->select('count(pa)')
            ->where('pa.isActive = true')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
