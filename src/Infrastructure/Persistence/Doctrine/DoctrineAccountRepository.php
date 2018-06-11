<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Account\Account;

class DoctrineAccountRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return Account::class;
    }
}
