<?php

namespace App\Infrastructure\Client\Centrifugo;

class PlatformNotificationMessage extends AbstractMessage
{
    private const EVENT_NAME = 'NEW_PLATFORM_NOTIFICATION';
    /** @var string */
    private $recipientId;

    /** @var string */
    private $condoId;

    /**
     * PlatformNotificationMessage constructor.
     *
     * @param string  $recipientId
     * @param ?string $condoId
     */
    public function __construct(string $recipientId, ?string $condoId)
    {
        $this->recipientId = $recipientId;
        $this->condoId = $condoId;
    }

    public function getChannelName()
    {
        return 'user-'.$this->recipientId;
    }

    public function getBody()
    {
        return [
            (object) [
                'event' => self::EVENT_NAME,
                'condo_id' => $this->condoId,
            ],
        ];
    }
}
