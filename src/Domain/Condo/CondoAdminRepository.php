<?php

namespace App\Domain\Condo;

use App\Domain\DomainRepository;

interface CondoAdminRepository extends DomainRepository
{
    public function getActiveCondoAdminsCount(Condo $condo): int;
}
