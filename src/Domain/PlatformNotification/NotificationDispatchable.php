<?php

namespace App\Domain\PlatformNotification;

interface NotificationDispatchable
{
    public function getPlatformNotification(): AbstractNotification;
}
