<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Transaction\Transaction;

class DoctrineTransactionRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return Transaction::class;
    }

    public function getTotalAmount(?string $year = null, ?string $month = null): ?float
    {

        $query = $this
            ->createQueryBuilder('tr')
            ->select('SUM(tr.amount)');

        if ($year && $month) {
            $periodStart = (new \DateTime())->setDate($year, $month, 1)->setTime(0, 0);
            $periodEnd = (new \DateTime())->setDate($year, $month, 1)
                ->modify('last day of')
                ->setTime(23, 59, 59);

            $query->andWhere('tr.date >= :periodStart ')
                ->andWhere('tr.date <= :periodEnd ')
                ->setParameter('periodStart', $periodStart)
                ->setParameter('periodEnd', $periodEnd);
        }

        return $query
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getTotalFee(?string $year = null, ?string $month = null): ?float
    {
        $query = $this
            ->createQueryBuilder('tr')
            ->select('SUM(tr.utilityFee)')
            ->where('tr.isYoinTransaction = false');
        if ($year && $month) {
            $periodStart = (new \DateTime())->setDate($year, $month, 1)->setTime(0, 0);
            $periodEnd = (new \DateTime())->setDate($year, $month, 1)
                ->modify('last day of')
                ->setTime(23, 59, 59);

            $query->andWhere('tr.date >= :periodStart ')
                ->andWhere('tr.date <= :periodEnd ')
                ->setParameter('periodStart', $periodStart)
                ->setParameter('periodEnd', $periodEnd);
        }
        return ($query
                ->getQuery()
                ->getSingleScalarResult()) / 2;
    }

    public function getTransactionCount(): ?int
    {

        $query = $this
            ->createQueryBuilder('tr')
            ->select('COUNT(tr)');

        return $query
            ->getQuery()
            ->getSingleScalarResult();
    }
}
