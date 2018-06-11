<?php

namespace App\Domain\ServiceRequest\Event;

use App\Domain\DomainEvent;
use App\Domain\ServiceRequest\ServiceRequestComment;

class ServiceRequestCommentCreatedEvent extends DomainEvent
{
    private $condoId;

    private $serviceRequestId;

    public function __construct(ServiceRequestComment $serviceRequestComment)
    {
        $this->condoId = $serviceRequestComment->getServiceRequest()->getCondo()->getId();
        $this->serviceRequestId = $serviceRequestComment->getServiceRequest()->getId();
    }

    public function condoId(): string
    {
        return $this->condoId;
    }

    public function serviceRequestId(): string
    {
        return $this->serviceRequestId;
    }
}
