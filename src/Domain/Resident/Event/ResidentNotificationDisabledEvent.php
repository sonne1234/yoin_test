<?php

namespace App\Domain\Resident\Event;

use App\Domain\Device\Device;
use App\Domain\Resident\Resident;

class ResidentNotificationDisabledEvent
{
    /** @var Resident */
    private $resident;

    /** @var Device */
    private $device;

    /**
     * ResidentCheckedIn constructor.
     *
     * @param Resident $resident
     */
    public function __construct(Resident $resident, Device $device = null)
    {
        $this->resident = $resident;
        $this->device = $device;
    }

    /**
     * @return Resident
     */
    public function getResident(): Resident
    {
        return $this->resident;
    }

    /**
     * @return Device
     */
    public function getDevice(): Device
    {
        return $this->device;
    }
}
