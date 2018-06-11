<?php

namespace App\Domain\Amenity\Factory;

use App\Domain\Amenity\Amenity;
use App\Domain\Amenity\AmenityAvailabilityData;
use App\Domain\Amenity\AmenityBookingData;
use App\Domain\Amenity\AmenityGeneralData;
use App\Domain\Amenity\AmenityTimeSlotsReBuilder;
use App\Domain\Condo\Condo;
use App\Domain\Condo\CondoBuilding;

class AmenityFactory
{
    /** @var AmenityTimeSlotsReBuilder */
    private $amenityTimeSlotsReBuilder;

    /**
     * AmenityFactory constructor.
     *
     * @param AmenityTimeSlotsReBuilder $amenityTimeSlotsReBuilder
     */
    public function __construct(AmenityTimeSlotsReBuilder $amenityTimeSlotsReBuilder)
    {
        $this->amenityTimeSlotsReBuilder = $amenityTimeSlotsReBuilder;
    }

    public function create(
        bool $isObservable,
        AmenityGeneralData $generalData,
        AmenityAvailabilityData $availabilityData,
        AmenityBookingData $bookingData,
        ?CondoBuilding $whoCanBook,
        Condo $condo,
        array $images)
    {
        $amenity = new Amenity($isObservable);
        $amenity->setGeneralData($generalData, $images);
        $amenity->setAvailabilityData($availabilityData, false);
        $amenity->setBookingData($bookingData, $whoCanBook, false);
        $amenity->setCondo($condo);

        $this->amenityTimeSlotsReBuilder->execute($amenity);

        return $amenity;
    }
}
