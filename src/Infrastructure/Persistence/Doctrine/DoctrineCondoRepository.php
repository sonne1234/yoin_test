<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Condo\Condo;
use App\Domain\Condo\CondoRepository;

class DoctrineCondoRepository extends AbstractDoctrineRepository implements CondoRepository
{
    protected function repositoryClassName(): string
    {
        return Condo::class;
    }

    public function getCondosCount(): int
    {
        return $this
            ->createQueryBuilder('c')
            ->select('count(c)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
