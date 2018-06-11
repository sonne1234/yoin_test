<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\User\UserIdentity;

class DoctrineUserRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return UserIdentity::class;
    }
}
