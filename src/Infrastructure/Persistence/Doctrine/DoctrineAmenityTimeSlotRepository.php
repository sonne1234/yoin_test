<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Amenity\Amenity;
use App\Domain\Amenity\AmenityTimeSlot;
use App\Domain\Amenity\AmenityTimeSlotRepository;
use App\Domain\Booking\Booking;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\ResultSetMapping;

class DoctrineAmenityTimeSlotRepository extends AbstractDoctrineRepository implements AmenityTimeSlotRepository
{
    private const SUM_CLAUSE_SQL = '
        SUM(COALESCE(
            (   
                CASE 
                WHEN t3.status = :status_cancelled THEN 0
                WHEN t3.status = :status_approval_declined THEN 0
                ELSE t3.placescount
                END
            ),
             0
        ))
    ';
    private const IS_BOOKED_BY_ADMIN_CLAUSE_SQL = '
        bool_or(
            CASE 
            WHEN t3.resident_id IS NULL AND t3.id IS NOT NULL THEN true
            ELSE false
            END
        ) AS isbookedbyadministration
    ';

    private const BOOKING_PER_TIMESLOT_SQL = '
        SUM(COALESCE(
              (
                CASE
                WHEN (t3.status <> :status_cancelled AND t3.status <> :status_approval_declined) THEN 1
                ELSE 0
                END
              ),
              0
          ))
    ';

    protected function repositoryClassName(): string
    {
        return AmenityTimeSlot::class;
    }

    public function getAmenityDatesForBookingTimeSlots(
        Amenity $amenity,
        bool $isGetOnlySlotsWithFreePlaces = true,
        \DateTime $fromDate = null
    ): array {
        [
            $limitBookingPerTimeslot,
            $isLimitBookingPerTimeslotShouldBeUsed,
        ] = $this->getParams($amenity);

        $result = array_map(
            function ($val) {
                return [
                    'id' => '',
                    'type' => AmenityTimeSlot::TYPE_TIME_ON_DEMAND,
                    'date' => \DateTime::createFromFormat('Y-m-d H:i:se', $val['date'])->format(DATE_ATOM),
                    'timeFrom' => null,
                    'timeTill' => null,
                    'freePlacesCount' => null,
                    'bookedPlacesCount' => null,
                    'isBookedByAdministration' => null,
                    'isOld' => null,
                ];
            },
            $this->fetchAll('
select date
from (
    select t1.date as date, t1.timeFrom as time
    from amenitytimeslot t1
    left join booking_amenitytimeslot t2 on (t1.id=t2.amenitytimeslot_id)
    left join booking t3 on (t2.booking_id = t3.id)
    where 
        ((t3.status != :status_cancelled and t3.status != :status_approval_declined) or t3.status is null) and
        t1.amenity_id= :amenityId and
         t1.isold = false and
         t1.type = :type and
         timefrom >= :timeFrom and
         date <= :dateTill
    group by t1.date, t1.timeFrom, t2.amenitytimeslot_id '.
    $this->getHavingClause(
        $isGetOnlySlotsWithFreePlaces,
        $isLimitBookingPerTimeslotShouldBeUsed,
        'amenitytimeslot_id'
    )
.' ) as t
group by date
order by date asc',
                array_merge(
                    $isGetOnlySlotsWithFreePlaces && $isLimitBookingPerTimeslotShouldBeUsed ?
                        ['limitBookingPerTimeslot' => $limitBookingPerTimeslot]
                        : [],
                    $isGetOnlySlotsWithFreePlaces ?
                        [
                            'capacity' => $amenity->getCapacity(),
                        ]
                        : [],
                    [
                        'status_cancelled' => Booking::STATUS_CANCELLED,
                        'status_approval_declined' => Booking::STATUS_APPROVAL_DECLINED,
                        'type' => AmenityTimeSlot::TYPE_TIME_ON_DEMAND,
                        'amenityId' => $amenity->getId(),
                        'timeFrom' => $fromDate
                            ? $fromDate->format(\DATE_ATOM)
                            : (new \DateTime())
                                ->setTimezone(new \DateTimeZone(AmenityTimeSlot::TIMEZONE))
                                ->format(\DATE_ATOM),
                        'dateTill' => $amenity
                            ->getLimitFutureBookingDate()
                            ->format(\DATE_ATOM),
                    ]
                )
            )
        );

        return $fromDate
            ? array_slice($result, 0, 7)
            : $result;
    }

    public function getAmenityTimeSlotsForBooking(
        Amenity $amenity,
        \DateTime $date,
        bool $isGetOnlySlotsWithFreePlaces = true,
        bool $checkCurrentDateTime = true
    ): ArrayCollection {
        [
            $limitBookingPerTimeslot,
            $isLimitBookingPerTimeslotShouldBeUsed,
            $freePlacesCountSql,
        ] = $this->getParams($amenity);

        $rsm = $this->createResultSetMapping();

        $sql = '
select
    t1.id,
    t1.type,
    t1.date,
    t1.timeFrom,
    t1.timeTill,
    t1.isOld,
    '.self::BOOKING_PER_TIMESLOT_SQL.' as bookedtimeslotscount,
    '.$freePlacesCountSql.' AS freeplacescount,
    '.self::SUM_CLAUSE_SQL.' AS bookedplacescount,
    '.self::IS_BOOKED_BY_ADMIN_CLAUSE_SQL.'
from amenitytimeslot t1
left join booking_amenitytimeslot t2 on (t1.id=t2.amenitytimeslot_id)
left join booking t3 on (t2.booking_id = t3.id)
where 
    t1.amenity_id= :amenityId and
     t1.isOld = false and
     t1.type = :type and
    '.($checkCurrentDateTime ? 'timeFrom >= :timeFrom1 and ' : '').'
     timeFrom >= :timeFrom2 and
     timeFrom <= :timeTill1 and
     timeFrom <= :timeTill2
group by t1.id, t1.type, t1.date, t1.timeFrom, t1.timeTill '.
$this->getHavingClause(
    $isGetOnlySlotsWithFreePlaces,
    $isLimitBookingPerTimeslotShouldBeUsed,
    'booking_id'
)
.'order by timeFrom asc';

        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->setParameters(
            array_merge(
                $isGetOnlySlotsWithFreePlaces && $isLimitBookingPerTimeslotShouldBeUsed ?
                    ['limitBookingPerTimeslot' => $limitBookingPerTimeslot]
                    : [],
                $checkCurrentDateTime
                    ? [
                        'timeFrom1' => (new \DateTime())
                            ->setTimezone(new \DateTimeZone(AmenityTimeSlot::TIMEZONE))
                            ->format(\DATE_ATOM),
                    ]
                    : [],
                [
                    'status_cancelled' => Booking::STATUS_CANCELLED,
                    'status_approval_declined' => Booking::STATUS_APPROVAL_DECLINED,
                    'amenityId' => $amenity->getId(),
                    'type' => AmenityTimeSlot::TYPE_TIME_ON_DEMAND,
                    'timeFrom2' => (clone $date)
                        ->setTimezone(new \DateTimeZone(AmenityTimeSlot::TIMEZONE))
                        ->setTime(0, 0, 0)
                        ->format(\DATE_ATOM),
                    'timeTill1' => (clone $date)
                        ->setTimezone(new \DateTimeZone(AmenityTimeSlot::TIMEZONE))
                        ->setTime(23, 59, 59)
                        ->format(\DATE_ATOM),
                    'timeTill2' => $amenity
                            ->getLimitFutureBookingDate(
                                (new \DateTime())
                                    ->setTimezone(new \DateTimeZone(AmenityTimeSlot::TIMEZONE))
                                    ->setTime(23, 59, 59)
                            )
                            ->format(\DATE_ATOM),
                    'capacity' => $amenity->getCapacity(),
                ]
            )
        );

        $result = $query->getResult();

        // force set bookedPlacesCount for admin to 0 if he can not book this slot already
        if (!$isGetOnlySlotsWithFreePlaces) {
            $this->forceClearBookedPlacesCountForAdminIfNeeded(
                $result,
                $isLimitBookingPerTimeslotShouldBeUsed,
                $limitBookingPerTimeslot,
                $amenity
            );
        }

        return new ArrayCollection($result);
    }

    public function getAmenityDaysForBooking(
        Amenity $amenity,
        bool $isGetOnlySlotsWithFreePlaces = true,
        \DateTime $fromDate = null
    ): ArrayCollection {
        [
            $limitBookingPerTimeslot,
            $isLimitBookingPerTimeslotShouldBeUsed,
            $freePlacesCountSql,
        ] = $this->getParams($amenity);

        $rsm = $this->createResultSetMapping();

        $sql = '
select
    t1.id,
    t1.type,
    t1.date,
    t1.timeFrom,
    t1.timeTill,
    t1.isOld,
    '.self::BOOKING_PER_TIMESLOT_SQL.' as bookedtimeslotscount,
    '.$freePlacesCountSql.' AS freeplacescount,
    '.self::SUM_CLAUSE_SQL.' AS bookedplacescount,
    '.self::IS_BOOKED_BY_ADMIN_CLAUSE_SQL.'
from amenitytimeslot t1
left join booking_amenitytimeslot t2 on (t1.id=t2.amenitytimeslot_id)
left join booking t3 on (t2.booking_id = t3.id)
where 
    t1.amenity_id= :amenityId and
     t1.isOld = false and
     t1.type = :type and
     date >= :dateFrom and
     date <= :dateTill 
group by t1.id, t1.type, t1.date, t1.timeFrom, t1.timeTill '.
$this->getHavingClause(
    $isGetOnlySlotsWithFreePlaces,
    $isLimitBookingPerTimeslotShouldBeUsed,
    'booking_id'
)
.'order by date asc';

        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->setParameters(
            array_merge(
                $isGetOnlySlotsWithFreePlaces && $isLimitBookingPerTimeslotShouldBeUsed ?
                    ['limitBookingPerTimeslot' => $limitBookingPerTimeslot]
                    : [],
                [
                    'status_cancelled' => Booking::STATUS_CANCELLED,
                    'status_approval_declined' => Booking::STATUS_APPROVAL_DECLINED,
                    'amenityId' => $amenity->getId(),
                    'type' => AmenityTimeSlot::TYPE_WHOLE_DAY,
                    'dateFrom' => $fromDate
                        ? $fromDate->format(\DATE_ATOM)
                        : (new \DateTime())
                            ->setTimezone(new \DateTimeZone(AmenityTimeSlot::TIMEZONE))
                            ->setTime(0, 0, 0)
                            ->format(\DATE_ATOM),
                    'dateTill' => $amenity
                        ->getLimitFutureBookingDate()
                        ->format(\DATE_ATOM),
                    'capacity' => $amenity->getCapacity(),
                ]
            )
        );

        $result = $fromDate
            ? array_slice($query->getResult(), 0, 7)
            : $query->getResult();

        // force set bookedPlacesCount for admin to 0 if he can not book this slot already
        if (!$isGetOnlySlotsWithFreePlaces) {
            $this->forceClearBookedPlacesCountForAdminIfNeeded(
                $result,
                $isLimitBookingPerTimeslotShouldBeUsed,
                $limitBookingPerTimeslot,
                $amenity
            );
        }

        return new ArrayCollection($result);
    }

    private function createResultSetMapping(): ResultSetMapping
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(AmenityTimeSlot::class, 't1');
        $rsm->addFieldResult('t1', 'id', 'id');
        $rsm->addFieldResult('t1', 'type', 'type');
        $rsm->addFieldResult('t1', 'date', 'date');
        $rsm->addFieldResult('t1', 'timefrom', 'timeFrom');
        $rsm->addFieldResult('t1', 'timetill', 'timeTill');
        $rsm->addFieldResult('t1', 'freeplacescount', 'freePlacesCount');
        $rsm->addFieldResult('t1', 'bookedplacescount', 'bookedPlacesCount');
        $rsm->addFieldResult('t1', 'isbookedbyadministration', 'isBookedByAdministration');
        $rsm->addFieldResult('t1', 'isold', 'isOld');
        $rsm->addFieldResult('t1', 'bookedtimeslotscount', 'bookedTimeslotsCount');

        return $rsm;
    }

    private function getParams(Amenity $amenity): array
    {
        $freePlacesCountSql = '(:capacity - '.self::SUM_CLAUSE_SQL.')';

        if ($amenity->getIsParallelBookingAllowed()) {
            $limitBookingPerTimeslot = $amenity->getLimitBookingPerTimeslot();
            $isLimitBookingPerTimeslotShouldBeUsed = (bool) $limitBookingPerTimeslot;
            if ($limitUsersPerBooking = $amenity->getLimitUsersPerBooking()) {
                $freePlacesCountSql = 'LEAST('.$freePlacesCountSql.', '.$limitUsersPerBooking.')';
            }
        } else {
            $isLimitBookingPerTimeslotShouldBeUsed = true;
            $limitBookingPerTimeslot = 1;
        }

        return [
            $limitBookingPerTimeslot,
            $isLimitBookingPerTimeslotShouldBeUsed,
            $freePlacesCountSql,
        ];
    }

    private function getHavingClause(
        bool $isGetOnlySlotsWithFreePlaces,
        bool $isLimitBookingPerTimeslotShouldBeUsed,
        string $fieldName
    ): string {
        return $isGetOnlySlotsWithFreePlaces
            ? (
                ' having '.self::SUM_CLAUSE_SQL.' < :capacity '
                .($isLimitBookingPerTimeslotShouldBeUsed ? ' AND '.self::BOOKING_PER_TIMESLOT_SQL.' < :limitBookingPerTimeslot ' : '')
            )
            : '';
    }

    private function forceClearBookedPlacesCountForAdminIfNeeded(
        iterable $items,
        bool $isLimitBookingPerTimeslotShouldBeUsed,
        int $limitBookingPerTimeslot,
        Amenity $amenity
    ) {
        array_walk($items, function (AmenityTimeSlot $ts) use (
            $isLimitBookingPerTimeslotShouldBeUsed,
            $limitBookingPerTimeslot,
            $amenity
        ) {
            if ($ts->getBookedPlacesCount() >= $amenity->getCapacity()) {
                $ts->emptyFreePlacesCount();
            } elseif ($isLimitBookingPerTimeslotShouldBeUsed &&
                $ts->bookedTimeslotsCount() >= $limitBookingPerTimeslot
            ) {
                $ts->emptyFreePlacesCount();
            }
        });
    }
}
