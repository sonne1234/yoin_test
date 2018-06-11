<?php

namespace App\Domain\NotificationGateway\Factory;

use App\Domain\NotificationGateway\Provider\SnsGatewayProvider;
use App\Domain\NotificationGateway\Topic;

class TopicFactory
{
    /** @var SnsGatewayProvider */
    private $gatewayProvider;

    /**
     * TopicFactory constructor.
     *
     * @param SnsGatewayProvider $gatewayProvider
     */
    public function __construct(SnsGatewayProvider $gatewayProvider)
    {
        $this->gatewayProvider = $gatewayProvider;
    }

    public function create($name)
    {
        $topic = (new Topic())->setName($name);
        $topicArn = $this->gatewayProvider->registerSnsTopic($topic->getName());
        $topic->setArn($topicArn);

        return $topic;
    }
}
