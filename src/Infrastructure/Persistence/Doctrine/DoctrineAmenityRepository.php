<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Amenity\Amenity;

class DoctrineAmenityRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return Amenity::class;
    }
}
