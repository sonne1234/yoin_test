<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Condo\Condo;
use App\Domain\Condo\CondoAdmin;
use App\Domain\Condo\CondoAdminRepository;

class DoctrineCondoAdminRepository extends AbstractDoctrineRepository implements CondoAdminRepository
{
    protected function repositoryClassName(): string
    {
        return CondoAdmin::class;
    }

    public function getActiveCondoAdminsCount(Condo $condo): int
    {
        return $this
            ->createQueryBuilder('ca')
            ->select('count(ca)')
            ->join('ca.condos', 'condo')
            ->where('ca.isActive = true')
            ->andWhere('condo = :condo')
            ->setParameter('condo', $condo)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
