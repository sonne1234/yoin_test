<?php

namespace App\Domain\Resident;

use App\Domain\Condo\CondoBuilding;
use App\Domain\DomainRepository;

interface ResidentRepository extends DomainRepository
{
    public function getResidentsCount(): int;

    public function getActiveResidentsCount(): int;

    public function getCondoFreeUnitsCount(string $condoId): int;

    public function getCondoBuildingFreeUnitsCount(CondoBuilding $condoBuilding): int;
}
