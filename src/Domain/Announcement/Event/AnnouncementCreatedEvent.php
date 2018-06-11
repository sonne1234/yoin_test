<?php

namespace App\Domain\Announcement\Event;

use App\Domain\Announcement\Announcement;
use App\Domain\DomainEvent;
use App\Domain\NotificationGateway\MassNotificationInterface;
use App\Domain\NotificationGateway\Message;

class AnnouncementCreatedEvent extends DomainEvent implements MassNotificationInterface
{
    private $topicArns = [];

    public function __construct(Announcement $announcement)
    {
        foreach ($announcement->getCondoBuildings() as $building) {
            $this->topicArns[] = $building->getResidentsTopic()->getArn();
        }
    }

    public function getMessage()
    {
        return new Message(Message::NEW_ANNOUNCEMENT, []);
    }

    public function getTopicArns()
    {
        return $this->topicArns;
    }
}
