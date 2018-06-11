<?php

namespace App\Domain\User\Event;

use App\Domain\DomainEvent;

class UserInitializedEvent extends DomainEvent
{
    private $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function userId(): string
    {
        return $this->userId;
    }
}
