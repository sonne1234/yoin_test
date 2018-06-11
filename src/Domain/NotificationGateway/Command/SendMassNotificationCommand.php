<?php


namespace App\Domain\NotificationGateway\Command;

use App\Domain\NotificationGateway\Message;
use JMS\Serializer\Annotation as JMS;

class SendMassNotificationCommand
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $topicArn;

    /**
     * @var Message
     * @JMS\Type("App\Domain\NotificationGateway\Message")
     */
    private $message;

    /**
     * SendMassNotificationCommand constructor.
     * @param string $topicArn
     * @param Message $message
     */
    public function __construct(string $topicArn, Message $message)
    {
        $this->topicArn = $topicArn;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getTopicArn(): string
    {
        return $this->topicArn;
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }
}