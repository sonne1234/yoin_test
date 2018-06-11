<?php

namespace App\Domain\NotificationGateway;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 */
class Subscription implements ContainsRecordedMessages
{
    use PrivateMessageRecorderCapabilities;
    use TimestampableEntity;

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var Topic
     * @ORM\ManyToOne(targetEntity="App\Domain\NotificationGateway\Topic")
     * @ORM\JoinColumn(nullable=false)
     */
    private $topic;

    /**
     * @var PlatformEndpoint
     * @ORM\ManyToOne(targetEntity="App\Domain\NotificationGateway\PlatformEndpoint")
     * @ORM\JoinColumn(nullable=false)
     */
    private $platformEndpoint;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $arn;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return Subscription
     */
    public function setId(string $id): Subscription
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Topic
     */
    public function getTopic(): Topic
    {
        return $this->topic;
    }

    /**
     * @param Topic $topic
     *
     * @return Subscription
     */
    public function setTopic(Topic $topic): Subscription
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return PlatformEndpoint
     */
    public function getPlatformEndpoint(): PlatformEndpoint
    {
        return $this->platformEndpoint;
    }

    /**
     * @param PlatformEndpoint $platformEndpoint
     *
     * @return Subscription
     */
    public function setPlatformEndpoint(PlatformEndpoint $platformEndpoint): Subscription
    {
        $this->platformEndpoint = $platformEndpoint;

        return $this;
    }

    /**
     * @return string
     */
    public function getArn(): string
    {
        return $this->arn;
    }

    /**
     * @param string $arn
     *
     * @return Subscription
     */
    public function setArn(string $arn): Subscription
    {
        $this->arn = $arn;

        return $this;
    }
}
