<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\SupportTicket\SupportTicket;

class DoctrineSupportTicketRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return SupportTicket::class;
    }
}
