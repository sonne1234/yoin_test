<?php

namespace App\Domain\PlatformNotification\Command;

use App\Domain\PlatformNotification\AbstractNotification;

class RegisterNotificationCommand
{
    /** @var AbstractNotification */
    private $notification;

    /**
     * RegisterNotificationCommand constructor.
     *
     * @param AbstractNotification $notification
     */
    public function __construct(AbstractNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return AbstractNotification
     */
    public function getNotification(): AbstractNotification
    {
        return $this->notification;
    }
}
