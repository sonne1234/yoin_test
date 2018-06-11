<?php

namespace App\Domain\NotificationGateway\Command;

use App\Domain\NotificationGateway\Message;
use JMS\Serializer\Annotation as JMS;

class SendSingleNotificationCommand
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $recipientId;

    /**
     * @var Message
     * @JMS\Type("App\Domain\NotificationGateway\Message")
     */
    private $message;

    /**
     * SendSingleNotificationCommand constructor.
     *
     * @param string  $recipientId
     * @param Message $message
     */
    public function __construct(string $recipientId, Message $message)
    {
        $this->recipientId = $recipientId;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getRecipientId(): string
    {
        return $this->recipientId;
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }
}
