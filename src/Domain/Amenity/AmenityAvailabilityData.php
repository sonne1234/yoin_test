<?php

namespace App\Domain\Amenity;

use App\Domain\Amenity\Exception\AmenityScheduleIsIncorrectException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class AmenityAvailabilityData
{
    const DAY_MONDAY = 'monday';
    const DAY_TUESDAY = 'tuesday';
    const DAY_WEDNESDAY = 'wednesday';
    const DAY_THURSDAY = 'thursday';
    const DAY_FRIDAY = 'friday';
    const DAY_SATURDAY = 'saturday';
    const DAY_SUNDAY = 'sunday';
    const DAY_EVERY = 'every';
    const DAYS_LIST = [
        self::DAY_MONDAY,
        self::DAY_TUESDAY,
        self::DAY_WEDNESDAY,
        self::DAY_THURSDAY,
        self::DAY_FRIDAY,
        self::DAY_SATURDAY,
        self::DAY_SUNDAY,
        self::DAY_EVERY,
    ];

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isDifferentForAllDays;

    /**
     * @var array bool
     * @ORM\Column(type="array", nullable=false)
     */
    private $days;

    /**
     * @var array bool
     * @ORM\Column(type="array", nullable=false)
     */
    private $daysProcessed;

    public function __construct(bool $isDifferentForAllDays, array $days)
    {
        $this->isDifferentForAllDays = $isDifferentForAllDays;
        $this->days = $days;
        $this->daysProcessed = $days;

        $this->validate();
    }

    public function toArray(): array
    {
        return [
            'days' => $this->days,
            'isDifferentForAllDays' => $this->isDifferentForAllDays,
        ];
    }

    public function getDaysProcessed(): array
    {
        return $this->daysProcessed;
    }

    private function validate()
    {
        if (!$this->isDifferentForAllDays) {
            if (1 !== count($this->daysProcessed)
                || $this->daysProcessed[0]['type'] !== self::DAY_EVERY
                || $this->daysProcessed[0]['isUnavailableForBooking']
            ) {
                throw new AmenityScheduleIsIncorrectException(
                    'Field "days" should contain only type "every" with "isUnavailableForBooking"=false'
                );
            }
        } else {
            if (in_array(self::DAY_EVERY, array_column($this->daysProcessed, 'type'))
                || count(array_unique(array_column($this->daysProcessed, 'type'))) !== count(array_column($this->daysProcessed, 'type'))
                || 7 !== count($this->daysProcessed)
            ) {
                throw new AmenityScheduleIsIncorrectException(
                    'Field "days" should contain 7 days'
                );
            }
        }

        foreach ($this->daysProcessed as &$day) {
            $day = $this->validateOneDay($day);
        }
        unset($day);
    }

    private function validateOneDay(array $day): array
    {
        // check and convert everything to DateTime
        [$day['from'], $day['till']] = $this->validatePeriod($day['from'], $day['till']);
        foreach ($day['gaps'] as &$gap) {
            [$gap['from'], $gap['till']] = $this->validatePeriod($gap['from'], $gap['till']);
        }
        unset($gap);

        if (count($day['gaps']) > 1) {
            // sort gaps
            usort($day['gaps'], function ($a, $b): int {
                if ($a['from'] < $b['from']) {
                    return -1;
                } elseif ($a['from'] > $b['from']) {
                    return 1;
                } else {
                    return 0;
                }
            });

            // validate that gaps don't intersect each other
            foreach ($day['gaps'] as $k => $gap) {
                if (!$nextGap = ($day['gaps'][$k + 1] ?? null)) {
                    break;
                }
                if ($nextGap['from'] < $gap['till']) {
                    throw new AmenityScheduleIsIncorrectException(
                        'Gaps in amenity schedule should not intersect each other.'
                    );
                }
            }
        }

        // check that gap inside $from & $till
        foreach ($day['gaps'] as $gap) {
            if ($gap['from'] < $day['from'] || $gap['till'] > $day['till']) {
                throw new AmenityScheduleIsIncorrectException(
                    'Every gap should be inside working time.'
                );
            }
        }

        return $day;
    }

    /**
     *   Check that $till greater than $from.
     *   And convert $till & $from to DateTime.
     */
    private function validatePeriod(array $from, array $till): array
    {
        $this->validateTime($from);
        $this->validateTime($till, true);

        $from = $this->getCurrentDate()->setTime($from['hrs'], $from['min']);
        $till = $this->getCurrentDate()->setTime(...(
            24 === $till['hrs']
                ? [23, 59]
                : [$till['hrs'], $till['min']]
        ));

        if ($from >= $till) {
            throw new AmenityScheduleIsIncorrectException();
        }

        return [$from, $till];
    }

    private function validateTime(array $time, bool $isTillTime = false)
    {
        $maxHrs = !$isTillTime ? 23 : 24;

        if ($time['hrs'] < 0 || $time['hrs'] > $maxHrs) {
            throw new AmenityScheduleIsIncorrectException();
        }
        if (0 !== $time['min'] && 30 !== $time['min']) {
            throw new AmenityScheduleIsIncorrectException();
        }

        if ($isTillTime && 24 === $time['hrs'] && 0 !== $time['min']) {
            throw new AmenityScheduleIsIncorrectException();
        }
    }

    private function getCurrentDate(): \DateTimeImmutable
    {
        static $date;
        if (is_null($date)) {
            $date = new \DateTimeImmutable(null, new \DateTimeZone(AmenityTimeSlot::TIMEZONE));
        }

        return clone $date;
    }
}
