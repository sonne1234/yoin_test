<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Amenity\AmenityTimeSlot;
use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingRepository;
use App\Domain\Condo\Condo;

class DoctrineBookingRepository extends AbstractDoctrineRepository implements BookingRepository
{
    public function getCountOfWaitingApprovalBookings(?Condo $condo, string $amenityId): int
    {
        if (!$condo && !$amenityId) {
            return 0;
        }

        $query = $this
            ->createQueryBuilder('b')
            ->select('count(distinct b)')
            ->join('b.timeSlots', 'ts')
            ->join('ts.amenity', 'amenity')
            ->andWhere('b.status = :status')
            ->andWhere('amenity.isObservable = true')
            ->andWhere('ts.date >= :currentDate')
            ->setParameters([
                'status' => Booking::STATUS_WAITING_APPROVAL,
                'currentDate' => (new \DateTime(null, new \DateTimeZone(AmenityTimeSlot::TIMEZONE)))
                    ->setTime(0, 0, 0),
            ]);

        if ($amenityId) {
            $query
                ->andWhere('amenity.id = :amenityId')
                ->setParameter('amenityId', $amenityId);
        }

        if ($condo) {
            $query
                ->andWhere('amenity.condo = :condo')
                ->setParameter('condo', $condo);
        }

        return $query
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getCondoAmenityRevenueChartData($condoId, \DateTime $from = null, \DateTime $to = null)
    {
        $chart = [];
        foreach ($this->getCondoAmenityRevenueData(true, $condoId, $from, $to) as $row) {
            $chart[$row['grp']] += $row['amount'];
        }

        foreach ($this->getCondoAmenityRevenueData(false, $condoId, $from, $to) as $row) {
            $chart[$row['grp']] += $row['amount'];
        }

        return $chart;
    }

    public function getCondoAmenityRevenueData($payedByCard = true, $condoId, \DateTime $from = null, \DateTime $to = null)
    {
        $groupField = $payedByCard ? 'paidByCardAt' : 'paidByCashAt';

        $groupMask = 'YYYY-MM-DD';

        $query = $this
            ->createQueryBuilder('b')
            ->select("date_format(b.$groupField, '$groupMask') as grp, SUM(b.paymentAmount) amount")
            ->groupBy('grp')
            ->andWhere("b.$groupField IS NOT NULL")
            ->join('b.amenity', 'aminity', 'WITH', 'aminity.condo = :condo')
            ->setParameter('condo', $condoId)
        ;

        if ($from) {
            $query->andWhere("b.$groupField >= :from")
                ->setParameter('from', $from);
        }

        if ($to) {
            $query->andWhere("b.$groupField <= :from")
                ->setParameter('from', $to);
        }

        $res1 = $query->getQuery()->getResult();

        return $res1 ?: [];
    }

    protected function repositoryClassName(): string
    {
        return Booking::class;
    }
}
