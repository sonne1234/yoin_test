<?php

namespace App\Domain\Platform;

use App\Domain\DomainRepository;

interface PlatformAdminRepository extends DomainRepository
{
    public function getActivePlatformAdminsCount(): int;
}
