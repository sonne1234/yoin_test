<?php

namespace App\Domain\Amenity;

use App\Domain\Booking\Booking;
use App\Domain\Booking\CreateBookingForAdministrationService;

class AmenityTimeSlotsReBuilder
{
    /**
     * @var AmenityTimeSlotsBuilder
     */
    private $amenityTimeSlotsBuilder;

    /**
     * @var CreateBookingForAdministrationService
     */
    private $createBookingForAdministrationService;

    public function __construct(
        AmenityTimeSlotsBuilder $amenityTimeSlotsBuilder,
        CreateBookingForAdministrationService $createBookingForAdministrationService
    ) {
        $this->amenityTimeSlotsBuilder = $amenityTimeSlotsBuilder;
        $this->createBookingForAdministrationService = $createBookingForAdministrationService;
    }

    /**
     * @param Amenity $amenity
     * @return array|Booking[]
     */
    public function execute(Amenity $amenity): array
    {
        $notCancelledAdministrationBookings = [];

        foreach ($amenity->getTimeSlots() as $ts) {
            if ($ts->getIsOld()) {
                continue;
            }
            $timeSlotHasBookings = false;
            foreach ($ts->getBookings() as $booking) {
                $timeSlotHasBookings = true;
                if ($booking->isAdministrationBooking() && !$booking->isCancelled()) {
                    $notCancelledAdministrationBookings[] = $booking;
                }
                !$booking->isDeclined() && $booking->cancel(
                    true,
                    CreateBookingForAdministrationService::CANCELLATION_REASON
                );
            }
            if ($timeSlotHasBookings) {
                $ts->markAsOld();
            } else {
                $amenity->removeTimeSlot($ts);
            }
        }

        $this->amenityTimeSlotsBuilder->execute($amenity);

        // rebook period for administration
        $createdBookings = [];
        foreach ($notCancelledAdministrationBookings as $booking) {
            if ($booking = $this->createBookingForAdministrationService->execute(
                $amenity,
                null,
                \DateTimeImmutable::createFromMutable(
                    $booking->getAdministrationBookingStartDate()
                ),
                \DateTimeImmutable::createFromMutable(
                    $booking->getAdministrationBookingEndDate()
                ),
                false
            )) {
                $createdBookings[] = $booking;
            }
        }

        return $createdBookings;
    }
}
