<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Condo\CondoBuilding;

class DoctrineCondoBuildingRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return CondoBuilding::class;
    }
}
