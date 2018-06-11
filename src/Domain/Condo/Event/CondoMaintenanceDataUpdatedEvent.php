<?php

namespace App\Domain\Condo\Event;

use App\Domain\Condo\Condo;
use App\Domain\DomainEvent;
use App\Domain\NotificationGateway\MassNotificationInterface;
use App\Domain\NotificationGateway\Message;

class CondoMaintenanceDataUpdatedEvent extends DomainEvent implements MassNotificationInterface
{
    /** @var array */
    private $topicArns = [];

    public function __construct(Condo $condo)
    {
        foreach ($condo->getBuildings() as $building) {
            if ($building->getPrimeResidentsTopic()) {
                $this->topicArns[] = $building->getPrimeResidentsTopic()->getArn();
            }
        }
    }

    public function getMessage()
    {
        return new Message(Message::MAINTENANCE_FEE_CHANGED, []);
    }

    public function getTopicArns()
    {
        return $this->topicArns;
    }
}
