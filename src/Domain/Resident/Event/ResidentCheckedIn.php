<?php

namespace App\Domain\Resident\Event;

use App\Domain\Condo\CondoBuilding;
use App\Domain\Resident\Resident;

class ResidentCheckedIn
{
    /** @var Resident */
    private $resident;

    /** @var CondoBuilding */
    private $building;

    /**
     * ResidentCheckedIn constructor.
     *
     * @param Resident      $resident
     * @param CondoBuilding $building
     */
    public function __construct(Resident $resident, CondoBuilding $building)
    {
        $this->resident = $resident;
        $this->building = $building;
    }

    /**
     * @return Resident
     */
    public function getResident(): Resident
    {
        return $this->resident;
    }

    /**
     * @return CondoBuilding
     */
    public function getBuilding(): CondoBuilding
    {
        return $this->building;
    }
}
