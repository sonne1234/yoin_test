<?php

namespace App\Domain\SupportTicket\Event;

use App\Domain\DomainEvent;
use App\Domain\NotificationGateway\Message;
use App\Domain\NotificationGateway\NotificationInterface;
use App\Domain\Resident\Resident;
use App\Domain\SupportTicket\SupportTicketComment;

class SupportTicketReplyCreatedEvent extends DomainEvent implements NotificationInterface
{
    /** @var Message */
    private $message;

    /** @var string */
    private $supportTicketResidentId;

    public function __construct(SupportTicketComment $supportTicketComment)
    {
        $this->message = new Message(Message::NEW_COMMENT_FOR_SUPPORT_TICKET, []);
        if ($supportTicketComment->getSupportTicket()->getUser() instanceof Resident) {
            $this->supportTicketResidentId = $supportTicketComment->getSupportTicket()->getUser()->getId();
        }
    }

    public function getMessageRecipientIds(): array
    {
        return [$this->supportTicketResidentId];
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}
