<?php

namespace App\Domain\Common\Event;

use App\Domain\DomainEvent;

class ImageCreatedEvent extends DomainEvent
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }
}
