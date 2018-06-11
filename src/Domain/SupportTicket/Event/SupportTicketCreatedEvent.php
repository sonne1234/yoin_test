<?php

namespace App\Domain\SupportTicket\Event;

use App\Domain\DomainEvent;
use App\Domain\PlatformNotification\AbstractNotification;
use App\Domain\PlatformNotification\NotificationDispatchable;
use App\Domain\PlatformNotification\Type\NewSupportTicketNotification;
use App\Domain\SupportTicket\SupportTicket;

class SupportTicketCreatedEvent extends DomainEvent implements NotificationDispatchable
{
    /** @var SupportTicket */
    private $supportTicket;

    /**
     * SupportTicketCreatedEvent constructor.
     *
     * @param SupportTicket $supportTicket
     */
    public function __construct(SupportTicket $supportTicket)
    {
        $this->supportTicket = $supportTicket;
    }

    public function getPlatformNotification(): AbstractNotification
    {
        return (new NewSupportTicketNotification())
            ->setAuthor($this->getCurrentUser())
            ->setTargetEntityId($this->supportTicket->getId())
            ->setCondo($this->supportTicket->getCondo())
            ->setMessageArgs([]);
    }
}
