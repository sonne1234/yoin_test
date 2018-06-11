<?php

namespace App\Domain\Unit;

use App\Domain\DomainRepository;

interface UnitRepository extends DomainRepository
{
    public function getUnitsCount(): int;
}
