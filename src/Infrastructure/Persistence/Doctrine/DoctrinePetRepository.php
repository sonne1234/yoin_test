<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Unit\Pet;

class DoctrinePetRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return Pet::class;
    }
}
