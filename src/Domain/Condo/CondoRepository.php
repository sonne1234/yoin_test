<?php

namespace App\Domain\Condo;

use App\Domain\DomainRepository;

interface CondoRepository extends DomainRepository
{
    public function getCondosCount(): int;
}
