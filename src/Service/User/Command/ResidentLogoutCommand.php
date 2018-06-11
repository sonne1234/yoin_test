<?php

namespace App\Service\User\Command;

use App\Domain\Resident\Resident;

class ResidentLogoutCommand
{
    /** @var Resident */
    private $resident;

    /**
     * ResidentLogoutCommand constructor.
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
