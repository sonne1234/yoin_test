<?php

namespace App\Domain\Condo\Criteria;

use App\Domain\Account\Account;
use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class CondoByAccountCriteria implements DomainCriteria
{
    /**
     * @var Account
     */
    private $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function create(): Criteria
    {
        return Criteria::create()
            ->where(Criteria::expr()->eq('account', $this->account))
            ->orderBy(['generalData.name' => Criteria::ASC]);
    }
}
