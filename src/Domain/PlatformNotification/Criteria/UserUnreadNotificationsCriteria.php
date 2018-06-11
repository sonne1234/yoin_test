<?php

namespace App\Domain\PlatformNotification\Criteria;

use App\Domain\DomainCriteria;
use App\Domain\User\UserIdentity;
use Doctrine\Common\Collections\Criteria;

class UserUnreadNotificationsCriteria implements DomainCriteria
{
    /** @var UserIdentity */
    private $userIdentity;

    /**
     * UserUnreadNotificationsCriteria constructor.
     *
     * @param UserIdentity $userIdentity
     */
    public function __construct(UserIdentity $userIdentity)
    {
        $this->userIdentity = $userIdentity;
    }

    public function create(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('notificationRecipients.user', $this->userIdentity))
            ->andWhere(Criteria::expr()->isNull('notificationRecipients.readAt'));
    }
}
