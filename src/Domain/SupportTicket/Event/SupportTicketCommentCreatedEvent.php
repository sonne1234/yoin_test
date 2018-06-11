<?php

namespace App\Domain\SupportTicket\Event;

use App\Domain\DomainEvent;

class SupportTicketCommentCreatedEvent extends DomainEvent
{
    private $supportTicketId;

    public function __construct(
        string $supportTicketId
    ) {
        $this->supportTicketId = $supportTicketId;
    }

    public function supportTicketId(): string
    {
        return $this->supportTicketId;
    }
}
