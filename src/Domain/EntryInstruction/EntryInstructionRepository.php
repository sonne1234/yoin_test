<?php

namespace App\Domain\EntryInstruction;

use App\Domain\Condo\Condo;
use App\Domain\DomainRepository;

interface EntryInstructionRepository extends DomainRepository
{
    public function getTodayCondoEntryInstructionsCount(Condo $condo): int;
}
