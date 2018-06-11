<?php

namespace App\Domain\Resident\Criteria;

use App\Domain\Account\Account;
use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class ResidentByPaymentProviderIdCriteria implements DomainCriteria
{

    private $paymentProviderId;

    public function __construct(string $paymentProviderId)
    {
        $this->paymentProviderId = $paymentProviderId;
    }

    public function create(): Criteria
    {
        return Criteria::create()
            ->where(Criteria::expr()->eq('paymentProviderId', $this->paymentProviderId));
    }
}
