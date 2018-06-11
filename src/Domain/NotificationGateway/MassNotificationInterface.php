<?php

namespace App\Domain\NotificationGateway;

interface MassNotificationInterface
{
    public function getMessage();

    public function getTopicArns();
}
