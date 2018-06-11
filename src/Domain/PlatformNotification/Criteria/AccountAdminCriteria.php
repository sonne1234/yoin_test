<?php

namespace App\Domain\PlatformNotification\Criteria;

use App\Domain\Account\Account;
use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class AccountAdminCriteria implements DomainCriteria
{
    /** @var Account */
    private $account;

    /**
     * AccountAdminCriteria constructor.
     *
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function create(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('isActive', true))
            ->andWhere(Criteria::expr()->eq('account', $this->account))
            ->andWhere(Criteria::expr()->eq('isNotificationsEnabled', true));
    }
}
