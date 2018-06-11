<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\ServiceRequest\ServiceRequestComment;

class DoctrineServiceRequestCommentRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return ServiceRequestComment::class;
    }
}
