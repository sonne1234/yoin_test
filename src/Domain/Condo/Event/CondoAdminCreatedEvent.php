<?php

namespace App\Domain\Condo\Event;

use App\Domain\Condo\CondoAdmin;
use App\Domain\Platform\Event\PlatformAdminCreatedEvent;
use App\Domain\PlatformNotification\AbstractNotification;
use App\Domain\PlatformNotification\NotificationDispatchable;
use App\Domain\PlatformNotification\Type\NewCondoAdminNotification;

class CondoAdminCreatedEvent extends PlatformAdminCreatedEvent implements NotificationDispatchable
{
    public function getPlatformNotification(): AbstractNotification
    {
        /** @var CondoAdmin $condoAdmin */
        $condoAdmin = $this->getUser();

        return (new NewCondoAdminNotification())
            ->setAuthor($this->getCurrentUser())
            ->setTargetEntityId($this->user->getId())
            ->setAccount($condoAdmin->getAccount())
            ->setMessageArgs([]);
    }
}
