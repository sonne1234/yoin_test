<?php

namespace App\Domain\Booking;

use App\Domain\Amenity\Amenity;
use App\Domain\Amenity\AmenityTimeSlotRepository;
use App\Domain\Booking\Event\BookingForAdministrationCreatedEvent;
use App\Domain\DomainEventPublisher;
use App\Domain\User\UserIdentity;

class CreateBookingForAdministrationService
{
    private const MAX_POSTGRESQL_INT_VAL = 2147480000;
    const CANCELLATION_REASON = 'Booking cancelled because a schedule of an amenity has changed.';

    /**
     * @var AmenityTimeSlotRepository
     */
    private $amenityTimeSlotRepository;

    public function __construct(
        AmenityTimeSlotRepository $amenityTimeSlotRepository
    ) {
        $this->amenityTimeSlotRepository = $amenityTimeSlotRepository;
    }

    public function execute(
        Amenity $amenity,
        ?UserIdentity $createdBy,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        bool $isNotifyResidents
    ): ?Booking {
        if (!count($timeSlots = $amenity->getTimeSlotsInsidePeriod($startDate, $endDate))) {
            return null;
        }

        foreach ($timeSlots as $ts) {
            foreach ($ts->getBookings() as $booking) {
                if (!$booking->isAdministrationBooking() && !$booking->isDeclined()) {
                    $booking->cancel(true, self::CANCELLATION_REASON);
                }
            }
        }

        $booking = (new Booking(
            $timeSlots,
            self::MAX_POSTGRESQL_INT_VAL,
            $createdBy,
            null,
            0,
            (new \DateTime(null, $startDate->getTimezone()))
                ->setTimestamp($startDate->getTimestamp()),
            (new \DateTime(null, $endDate->getTimezone()))
                ->setTimestamp($endDate->getTimestamp())
        ))
            ->markAsCompleted();

        DomainEventPublisher::instance()->publish(
            new BookingForAdministrationCreatedEvent($amenity->getId(), $isNotifyResidents)
        );

        return $booking;
    }
}
