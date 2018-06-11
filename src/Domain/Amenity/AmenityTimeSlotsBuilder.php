<?php

namespace App\Domain\Amenity;

class AmenityTimeSlotsBuilder
{
    /** @var bool  */
    private $isUseMaxLimitFutureBookingMonthsConst;

    public function __construct(bool $isUseMaxLimitFutureBookingMonthsConst = true)
    {
        $this->isUseMaxLimitFutureBookingMonthsConst = $isUseMaxLimitFutureBookingMonthsConst;
    }

    public function execute(Amenity $amenity)
    {
        $currentDate = (new \DateTime(null, new \DateTimeZone(AmenityTimeSlot::TIMEZONE)))
            ->setTime(0, 0, 0);

        // build timeslots from current date if there are no timeslots in amenity
        // or build timeslots from the latest timeslot in amenity
        $beginDate = ($timeSlot = $amenity->getTheLatestTimeSlot())
            ? $timeSlot->getDate()->modify('+1 day')
            : clone $currentDate;
        $endDate = $amenity
            ->getLimitFutureBookingDate($currentDate, $this->isUseMaxLimitFutureBookingMonthsConst)
            ->modify('+2 day'); // for sure

        while ($beginDate <= $endDate) {
            $this->buildTimeSlotsForDate($amenity, clone $beginDate);
            $beginDate->modify('+1 day');
        }
    }

    private function buildTimeSlotsForDate(Amenity $amenity, \DateTime $date)
    {
        if (is_null($amenity->getBookingData()) ||
            !($amenityDays = $amenity->getAvailabilityDataDaysProcessed()) ||
            is_null($amenity->getGeneralData())
        ) {
            return;
        }

        $dayOfWeek = mb_strtolower($date->format('l'));

        if (count($amenityDays) === 1) {
            $daySchedule = $amenityDays[0];
        } elseif (count($daySchedule = array_values(array_filter(
            $amenityDays,
            function ($val) use ($dayOfWeek) {
                return  $val['type'] === $dayOfWeek;
            }
        )))) {
            $daySchedule = $daySchedule[0];
        } else {
            return;
        }

        if ($daySchedule['isUnavailableForBooking'] ?? true) {
            return;
        }

        // correct time of work and gaps for date which we are processed
        foreach (['from', 'till'] as $type) {
            $daySchedule[$type] = $daySchedule[$type]->setDate(
                $date->format('Y'),
                $date->format('m'),
                $date->format('d')
            );
        }
        foreach ($daySchedule['gaps'] as &$gap) {
            foreach (['from', 'till'] as $type) {
                $gap[$type] = $gap[$type]->setDate(
                    $date->format('Y'),
                    $date->format('m'),
                    $date->format('d')
                );
            }
        }
        unset($gap);

        if ($amenity->getIsBookingForWholeDay()) {
            $workMinutes = $this->getPeriodLengthInMinutes($daySchedule['from'], $daySchedule['till']);
            $gapsMinutes = 0;
            foreach ($daySchedule['gaps'] as $gap) {
                $gapsMinutes += $this->getPeriodLengthInMinutes($gap['from'], $gap['till']);
            }
            if ($workMinutes - $gapsMinutes > 0) {
                $amenity->addTimeSlot(new AmenityTimeSlot(
                    AmenityTimeSlot::TYPE_WHOLE_DAY,
                    $date
                ));
            }
        } elseif ($timeslotDuration = $amenity->getTimeslotDuration()) {
            $current = $daySchedule['from'];
            while ($current < $daySchedule['till']) {
                if (!is_array($res = $this->isTimeslotCanBeFit(
                    $daySchedule['gaps'],
                    $current,
                    $daySchedule['till'],
                    $timeslotDuration
                ))) {
                    $current = $res;
                } else {
                    [$begin, $end] = $res;
                    $amenity->addTimeSlot(new AmenityTimeSlot(
                        AmenityTimeSlot::TYPE_TIME_ON_DEMAND,
                        $date,
                        $begin,
                        $end
                    ));
                    $current = $end;
                }
            }
        }
    }

    private function getPeriodLengthInMinutes(\DateTimeImmutable $dateFrom, \DateTimeImmutable $dateTill): int
    {
        $diff = $dateTill->diff($dateFrom);

        return $diff->i + $diff->h * 60 + ($dateTill->format('i') == 59 ? 1 : 0);
    }

    /**
     * @return array|\DateTimeImmutable
     */
    private function isTimeslotCanBeFit(
        array &$gaps,
        \DateTimeImmutable $fromDate,
        \DateTimeImmutable $end,
        int $duration
    ) {
        $tillDate = $fromDate->modify("+${duration} minutes");

        if ($tillDate->format('H:i') == '00:00' &&
            $fromDate->format('Y-m-d') === $tillDate->modify('-1 minutes')->format('Y-m-d')
        ) {
            $tillDate = $tillDate->modify('-1 minutes');
        }

        if ($tillDate > $end) {
            return $tillDate;
        }

        foreach ($gaps as $k => $gap) {
            if (($gap['from'] > $fromDate && $gap['from'] < $tillDate) ||
                ($gap['till'] > $fromDate && $gap['till'] < $tillDate) ||
                $gap['from'] == $fromDate ||
                $gap['till'] == $tillDate ||
                ($gap['from'] < $fromDate && $gap['till'] > $tillDate)
            ) {
                $res = clone $gap['till'];
                unset($gaps[$k]);

                return $res;
            }
        }

        return [$fromDate, $tillDate];
    }
}
