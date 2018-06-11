<?php

namespace App\Domain\Condo\Event;

use App\Domain\Condo\CondoBuilding;

class CondoBuildingCreatedEvent
{
    /** @var CondoBuilding */
    private $condoBuilding;

    /**
     * CondoBuildingCreatedEvent constructor.
     *
     * @param CondoBuilding $condoBuilding
     */
    public function __construct(CondoBuilding $condoBuilding)
    {
        $this->condoBuilding = $condoBuilding;
    }

    /**
     * @return CondoBuilding
     */
    public function getCondoBuilding(): CondoBuilding
    {
        return $this->condoBuilding;
    }
}
