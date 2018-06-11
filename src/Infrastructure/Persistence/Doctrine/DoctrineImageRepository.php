<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Common\Image;

class DoctrineImageRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return Image::class;
    }
}
