<?php

namespace App\Domain\NotificationGateway;

use App\Domain\User\UserIdentity;

interface NotificationInterface
{
    public function getMessage(): Message;

    public function getMessageRecipientIds(): array;

    public function getCurrentUser(): ?UserIdentity;
}
