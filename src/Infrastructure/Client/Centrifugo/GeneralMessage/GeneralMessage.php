<?php

namespace App\Infrastructure\Client\Centrifugo\GeneralMessage;

use App\Infrastructure\Client\Centrifugo\AbstractMessage;

abstract class GeneralMessage extends AbstractMessage
{
    public function getChannelName()
    {
        return 'general';
    }

    public function getBody()
    {
        return [
            (object) array_merge(
                [
                    'event' => $this->getEventName(),
                ],
                $this->getPayload()
            ),
        ];
    }

    abstract public function getPayload(): array;

    abstract public function getEventName(): string;
}
