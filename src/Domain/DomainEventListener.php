<?php

namespace App\Domain;

interface DomainEventListener
{
    public function handle(DomainEvent $event): void;

    public function isSubscribedTo(DomainEvent $event): bool;
}
