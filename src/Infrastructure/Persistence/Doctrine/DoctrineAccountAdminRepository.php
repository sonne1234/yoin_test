<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Account\AccountAdmin;

class DoctrineAccountAdminRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return AccountAdmin::class;
    }
}
