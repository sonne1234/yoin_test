<?php

namespace App\Domain\ServiceRequest\Event;

use App\Domain\DomainEvent;
use App\Domain\PlatformNotification\AbstractNotification;
use App\Domain\PlatformNotification\NotificationDispatchable;
use App\Domain\PlatformNotification\Type\NewServiceRequestNotification;
use App\Domain\ServiceRequest\ServiceRequest;

class ServiceRequestCreatedEvent extends DomainEvent implements NotificationDispatchable
{
    /** @var ServiceRequest */
    private $serviceRequest;

    /**
     * ServiceRequestCreatedEvent constructor.
     *
     * @param ServiceRequest $serviceRequest
     */
    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
    }

    public function getPlatformNotification(): AbstractNotification
    {
        return (new NewServiceRequestNotification())
            ->setAuthor($this->getCurrentUser())
            ->setTargetEntityId($this->serviceRequest->getId())
            ->setCondo($this->serviceRequest->getCondo())
            ->setMessageArgs([]);
    }
}
