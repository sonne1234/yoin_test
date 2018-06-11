<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Announcement\Announcement;

class DoctrineAnnouncementRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return Announcement::class;
    }
}
