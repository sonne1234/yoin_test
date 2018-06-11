<?php

namespace App\Infrastructure\Client\Centrifugo\GeneralMessage;

class CountOfWABookingsChangedMessage extends GeneralMessage
{
    /** @var string  */
    private $condoId;

    /** @var string */
    private $amenityId;

    public function __construct(string $condoId, string $amenityId)
    {
        $this->condoId = $condoId;
        $this->amenityId = $amenityId;
    }

    public function getPayload(): array
    {
        return [
            'condo_id' => $this->condoId,
            'amenity_id' => $this->amenityId,
        ];
    }

    public function getEventName(): string
    {
        return 'COUNT_OF_WAITING_APPROVAL_BOOKINGS_CHANGED';
    }
}
