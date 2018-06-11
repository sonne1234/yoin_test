<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Invoice\MaintenanceFeeInvoice;

class DoctrineMaintenanceFeeInvoiceRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return MaintenanceFeeInvoice::class;
    }

    public function getCondoMaintenanceRevenueChartData($condoId, \DateTime $from = null, \DateTime $to = null)
    {
        $groupMask = 'YYYY-MM-DD';

        $query = $this
            ->createQueryBuilder('invoice')
            ->select("date_format(invoice.paidAt, '$groupMask') as grp, SUM(invoice.amount) amount")
            ->groupBy('grp')
            ->where('invoice.isPaid = true')
            ->andWhere('invoice.paidAt IS NOT NULL')
            ->join('invoice.unit', 'unit', 'WITH', 'unit.condo = :condo')
            ->setParameter('condo', $condoId);

        if ($from) {
            $query->andWhere('invoice.paidAt >= :from')
            ->setParameter('from', $from);
        }

        if ($to) {
            $query->andWhere('invoice.paidAt <= :from')
            ->setParameter('from', $to);
        }

        $res = $query->getQuery()->getResult();

        $chart = [];
        if ($res) {
            foreach ($res as $row) {
                $chart[$row['grp']] += $row['amount'];
            }
        }

        return $chart;
    }

    public function getCondoMaintenanceDebtChartData($condoId, \DateTime $from = null, \DateTime $to = null)
    {
        $groupMask = 'YYYY-MM-DD';

        $query = $this
            ->createQueryBuilder('invoice')
            ->select("date_format(invoice.payPeriod, '$groupMask') as grp, SUM(invoice.amount) amount")
            ->groupBy('grp')
            ->where('invoice.isPaid = false')
            ->andWhere('invoice.payPeriod < :now')
            ->join('invoice.unit', 'unit', 'WITH', 'unit.condo = :condo')
            ->setParameter('now', new \DateTime())
            ->setParameter('condo', $condoId);

        if ($from) {
            $query->andWhere('invoice.payPeriod >= :from')
                ->setParameter('from', $from);
        }

        if ($to) {
            $query->andWhere('invoice.payPeriod <= :from')
                ->setParameter('from', $to);
        }

        $res = $query->getQuery()->getResult();

        $chart = [];
        if ($res) {
            foreach ($res as $row) {
                $chart[$row['grp']] += $row['amount'];
            }
        }

        return $chart;
    }
}
