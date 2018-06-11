<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Unit\Unit;
use App\Domain\Unit\UnitRepository;

class DoctrineUnitRepository extends AbstractDoctrineRepository implements UnitRepository
{
    protected function repositoryClassName(): string
    {
        return Unit::class;
    }

    public function getUnitsCount(): int
    {
        return $this
            ->createQueryBuilder('u')
            ->select('count(u)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
