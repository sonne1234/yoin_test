<?php

namespace App\Domain\Amenity;

use App\Domain\DomainRepository;
use Doctrine\Common\Collections\ArrayCollection;

interface AmenityTimeSlotRepository extends DomainRepository
{
    public function getAmenityDatesForBookingTimeSlots(
        Amenity $amenity,
        bool $isGetOnlySlotsWithFreePlaces = true,
        \DateTime $fromDate = null
    ): array;

    public function getAmenityDaysForBooking(
        Amenity $amenity,
        bool $isGetOnlySlotsWithFreePlaces = true,
        \DateTime $fromDate = null
    ): ArrayCollection;

    public function getAmenityTimeSlotsForBooking(
        Amenity $amenity,
        \DateTime $date,
        bool $isGetOnlySlotsWithFreePlaces = true,
        bool $checkCurrentDateTime = true
    ): ArrayCollection;
}
