<?php

namespace App\Domain\Condo\Event;

use App\Domain\DomainEvent;

class CondoPaymentDataChangedEvent extends DomainEvent
{
    private $condoId;

    public function __construct(
        string $condoId
    ) {
        $this->condoId = $condoId;
    }

    public function condoId(): string
    {
        return $this->condoId;
    }
}
