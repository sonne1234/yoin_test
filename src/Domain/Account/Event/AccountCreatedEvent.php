<?php

namespace App\Domain\Account\Event;

use App\Domain\Account\Account;
use App\Domain\DomainEvent;
use App\Domain\PlatformNotification\AbstractNotification;
use App\Domain\PlatformNotification\NotificationDispatchable;
use App\Domain\PlatformNotification\Type\NewAccountNotification;

class AccountCreatedEvent extends DomainEvent implements NotificationDispatchable
{
    /** @var Account */
    private $account;

    /**
     * AccountCreatedEvent constructor.
     *
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function getPlatformNotification(): AbstractNotification
    {
        return (new NewAccountNotification())
            ->setAuthor($this->getCurrentUser())
            ->setTargetEntityId($this->account->getId())
            ->setMessageArgs([$this->account->getAccountCompanyName()]);
    }
}
