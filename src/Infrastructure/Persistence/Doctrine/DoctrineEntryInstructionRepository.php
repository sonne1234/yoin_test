<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Amenity\AmenityTimeSlot;
use App\Domain\Condo\Condo;
use App\Domain\EntryInstruction\EntryInstruction;
use App\Domain\EntryInstruction\EntryInstructionRepository;

class DoctrineEntryInstructionRepository extends AbstractDoctrineRepository implements EntryInstructionRepository
{
    protected function repositoryClassName(): string
    {
        return EntryInstruction::class;
    }

    public function getTodayCondoEntryInstructionsCount(Condo $condo): int
    {
        $qb = $this->createQueryBuilder('i');

        return $qb
            ->select('count(i)')
            ->join('i.condo', 'condo')
            ->andWhere('condo = :condo')
            ->andWhere('i.isCanceled = false')
            ->andWhere($qb->expr()->orX(
                $qb->expr()->andX(
                    $qb->expr()->isNull('i.periodEnd'),
                    $qb->expr()->lte('i.periodStart', ':todayDate')
                ),
                $qb->expr()->andX(
                    $qb->expr()->isNotNull('i.periodEnd'),
                    $qb->expr()->lte('i.periodStart', ':todayDate'),
                    $qb->expr()->gte('i.periodEnd', ':todayDate')
                )
            ))
            ->setParameters([
                'condo' => $condo,
                'todayDate' => (new \DateTime(
                    null,
                    new \DateTimeZone(AmenityTimeSlot::TIMEZONE)
                ))->format('Y-m-d')
            ])
            ->getQuery()
            ->getSingleScalarResult();
    }
}
