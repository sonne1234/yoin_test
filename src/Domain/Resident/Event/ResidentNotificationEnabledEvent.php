<?php

namespace App\Domain\Resident\Event;

use App\Domain\Resident\Resident;

class ResidentNotificationEnabledEvent
{
    /** @var Resident */
    private $resident;

    /**
     * ResidentCheckedIn constructor.
     *
     * @param Resident $resident
     */
    public function __construct(Resident $resident)
    {
        $this->resident = $resident;
    }

    /**
     * @return Resident
     */
    public function getResident(): Resident
    {
        return $this->resident;
    }
}
