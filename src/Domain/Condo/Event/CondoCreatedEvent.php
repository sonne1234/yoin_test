<?php

namespace App\Domain\Condo\Event;

use App\Domain\Condo\Condo;
use App\Domain\DomainEvent;
use App\Domain\PlatformNotification\AbstractNotification;
use App\Domain\PlatformNotification\NotificationDispatchable;
use App\Domain\PlatformNotification\Type\NewCondoNotification;

class CondoCreatedEvent extends DomainEvent implements NotificationDispatchable
{
    /** @var Condo */
    private $condo;

    /**
     * CondoCreatedEvent constructor.
     *
     * @param Condo $condo
     */
    public function __construct(Condo $condo)
    {
        $this->condo = $condo;
    }

    public function getPlatformNotification(): AbstractNotification
    {
        return (new NewCondoNotification())
            ->setAuthor($this->getCurrentUser())
            ->setTargetEntityId($this->condo->getId())
            ->setAccount($this->condo->getAccount())
            ->setMessageArgs([$this->condo->getName()]);
    }
}
