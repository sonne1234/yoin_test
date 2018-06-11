<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\EntryInstruction\EntryInstructionLog;

class DoctrineEntryInstructionLogRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return EntryInstructionLog::class;
    }
}
