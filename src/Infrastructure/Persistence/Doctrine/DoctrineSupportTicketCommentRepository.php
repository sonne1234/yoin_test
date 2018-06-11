<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\SupportTicket\SupportTicketComment;

class DoctrineSupportTicketCommentRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return SupportTicketComment::class;
    }
}
