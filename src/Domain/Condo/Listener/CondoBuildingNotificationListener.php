<?php

namespace App\Domain\Condo\Listener;

use App\Domain\Condo\Event\CondoBuildingCreatedEvent;
use App\Domain\NotificationGateway\Factory\TopicFactory;

class CondoBuildingNotificationListener
{
    private const RESIDENTS_TOPIC_PREFIX = 'residents_';
    private const PRIME_RESIDENTS_TOPIC_PREFIX = 'prime_residents_';

    /** @var TopicFactory */
    private $topicFactory;

    /**
     * CondoBuildingNotificationListener constructor.
     *
     * @param TopicFactory $topicFactory
     */
    public function __construct(TopicFactory $topicFactory)
    {
        $this->topicFactory = $topicFactory;
    }

    public function createCondoBuildingTopics(CondoBuildingCreatedEvent $event)
    {
        $condoBuilding = $event->getCondoBuilding();
        $condoBuilding->setResidentsTopic($this->topicFactory->create(self::RESIDENTS_TOPIC_PREFIX.$condoBuilding->getId()));
        $condoBuilding->setPrimeResidentsTopic($this->topicFactory->create(self::PRIME_RESIDENTS_TOPIC_PREFIX.$condoBuilding->getId()));
    }
}
