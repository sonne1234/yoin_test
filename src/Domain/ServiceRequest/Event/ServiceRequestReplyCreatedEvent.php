<?php

namespace App\Domain\ServiceRequest\Event;

use App\Domain\DomainEvent;
use App\Domain\NotificationGateway\Message;
use App\Domain\NotificationGateway\NotificationInterface;
use App\Domain\ServiceRequest\ServiceRequestComment;

class ServiceRequestReplyCreatedEvent extends DomainEvent implements NotificationInterface
{
    /** @var Message */
    private $message;

    /** @var string */
    private $serviceRequestResidentId;

    public function __construct(ServiceRequestComment $serviceRequestComment)
    {
        $this->message = new Message(Message::NEW_COMMENT_FOR_SERVICE_REQUEST, []);
        $this->serviceRequestResidentId = $serviceRequestComment->getServiceRequest()->getResident()->getId();
    }

    public function getMessageRecipientIds(): array
    {
        return [$this->serviceRequestResidentId];
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}
