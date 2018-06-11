<?php

namespace App\Domain\Condo\Criteria;

use App\Domain\Account\Account;
use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class CondoByPaymentProviderAccountCriteria implements DomainCriteria
{

    private $paymentProviderAccountId;

    public function __construct(string $paymentProviderAccountId)
    {
        $this->paymentProviderAccountId = $paymentProviderAccountId;
    }

    public function create(): Criteria
    {
        return Criteria::create()
            ->where(Criteria::expr()->eq('paymentAccountId', $this->paymentProviderAccountId))
            ->orderBy(['generalData.name' => Criteria::ASC]);
    }
}
