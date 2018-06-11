<?php

namespace App\Domain\Booking;

use App\Domain\Amenity\AmenityTimeSlot;
use App\Domain\Amenity\AmenityTimeSlotRepository;
use App\Domain\Booking\Exception\SlotsDoNotHaveEnoughFreePlacesForBookingException;
use App\Domain\Booking\Exception\SlotsUnavailableForBookingException;
use App\Domain\Booking\Exception\WrongBookingRequestDataException;
use App\Domain\Resident\Resident;
use App\Domain\User\UserIdentity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class CreateBookingService
{
    /**
     * @var AmenityTimeSlotRepository
     */
    private $amenityTimeSlotRepository;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(
        AmenityTimeSlotRepository $amenityTimeSlotRepository,
        EntityManager $entityManager
    ) {
        $this->amenityTimeSlotRepository = $amenityTimeSlotRepository;
        $this->em = $entityManager;
    }

    /**
     * @param AmenityTimeSlot[] $timeSlots
     * @param int               $placesCount
     * @param int               $amount
     * @param UserIdentity      $createdBy
     * @param Resident|null     $resident
     *
     * @return Booking
     */
    public function execute(
        array $timeSlots,
        int $placesCount,
        int $amount,
        UserIdentity $createdBy,
        ?Resident $resident
    ): Booking {
        if (!$placesCount) {
            throw new WrongBookingRequestDataException();
        }

        if (!$timeSlots) {
            throw new SlotsUnavailableForBookingException();
        }

        if (!$amenity = $timeSlots[0]->getAmenity()) {
            throw new \LogicException('Amenity can not be empty!');
        }
        $date = $timeSlots[0]->getDate();

        // all timeslots should be in the same amenity
        foreach ($timeSlots as $slot) {
            if ($amenity !== $slot->getAmenity()) {
                throw new WrongBookingRequestDataException(
                    'All timeslots should be in the same amenity.'
                );
            }
        }

        // time slots should be in one day
        if (!$amenity->getIsBookingForWholeDay()) {
            foreach ($timeSlots as $slot) {
                if ($date != $slot->getDate()) {
                    throw new WrongBookingRequestDataException(
                        'All timeslots should be in the same day.'
                    );
                }
            }
        }

        // check count of timeslots
        if ($amenity->getCountOfTimeslotsForOneBooking() < count($timeSlots)) {
            throw new WrongBookingRequestDataException(
                "You can book only {$amenity->getCountOfTimeslotsForOneBooking()} slots in one request."
            );
        }

        // because of synchronization of freePlacesCount
        // detach all slots and then get it fresh from database query with right value of freePlacesCount
        $timeSlotsIds = [];
        foreach ($timeSlots as $slot) {
            $timeSlotsIds[] = $slot->getId();
            $this->em->detach($slot);
        }
        $timeSlots = [];

        /** @var ArrayCollection|AmenityTimeSlot[] $availableSlotsForBooking */
        $availableSlotsForBooking = $amenity->getIsBookingForWholeDay()
            ? $this->amenityTimeSlotRepository->getAmenityDaysForBooking($amenity)
            : $this->amenityTimeSlotRepository->getAmenityTimeSlotsForBooking($amenity, $date);

        // check that slots available for needed $placesCount
        foreach ($availableSlotsForBooking as $slot) {
            if (in_array($slot->getId(), $timeSlotsIds)) {
                if ($slot->getFreePlacesCount() < $placesCount) {
                    throw new SlotsDoNotHaveEnoughFreePlacesForBookingException();
                }
                $timeSlots[] = $slot;
            }
        }

        // check that all slots exists
        if (count($timeSlots) !== count($timeSlotsIds)) {
            throw new SlotsUnavailableForBookingException();
        }

        if ($amenity->getIsBookingForWholeDay()) {
            // slots should be day by day
            usort(
                $timeSlots,
                function (AmenityTimeSlot $a, AmenityTimeSlot $b) {
                    if ($a->getDate() < $b->getDate()) {
                        return -1;
                    } elseif ($a->getDate() > $b->getDate()) {
                        return 1;
                    }

                    return 0;
                }
            );
            $startedId = 0;
            foreach ($timeSlots as $key => $slot) {
                foreach ($availableSlotsForBooking as $key2 => $slot2) {
                    if ($slot === $slot2) {
                        $startedId = $key2;
                        break 2;
                    }
                }
            }
            foreach ($timeSlots as $slot) {
                if ($availableSlotsForBooking->get($startedId++) !== $slot) {
                    throw new WrongBookingRequestDataException(
                        'Timeslots should be day by day'
                    );
                }
            }
        }

        // sort timeslots in one day
        if (!$amenity->getIsBookingForWholeDay()) {
            usort(
                $timeSlots,
                function (AmenityTimeSlot $a, AmenityTimeSlot $b) {
                    return $a->getTimeFrom() <=> $b->getTimeFrom();
                }
            );
        }

        // reload timeslots to get full info
        array_walk(
            $timeSlots,
            function (AmenityTimeSlot $slot) {
                $this->em->refresh($slot);
            }
        );

        $booking = new Booking(
            $timeSlots,
            $placesCount,
            $createdBy,
            $resident,
            $amount
        );

        if ($amenity->getIsAmenityPaid()) {
            $booking->markAsWaitingPayment();
        } elseif ($amenity->getIsBookingRequiresApproval()) {
            $booking->markAsWaitingApproval();
        } else {
            $booking->markAsCompleted();
        }

        return $booking;
    }
}
