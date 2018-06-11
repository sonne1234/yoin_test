<?php

namespace App\Domain\EntryInstruction\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\EntryInstruction\EntryInstructionLog;

class EntryInstructionLogTransformer extends DomainTransformer
{
    /**
     * @param EntryInstructionLog $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'arriveAt' => $entity->getArriveAt() ? $entity->getArriveAt()->format(DATE_ATOM) : null,
            'exitAt' => $entity->getExitAt() ? $entity->getExitAt()->format(DATE_ATOM) : null,
            'createdAt' => $entity->getCreatedAt()->format(DATE_ATOM),
        ];
    }
}
