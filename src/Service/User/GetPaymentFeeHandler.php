<?php

namespace App\Service\User;

use App\Service\AbstractHandler;
use App\Domain\Resident\Resident;

class GetPaymentFeeHandler extends AbstractHandler
{
    public function __invoke()
    {
        if (!$this->currentUser instanceof Resident) {
            throw new \LogicException('Only residents can do this query');
        }

        return $this->currentUser->getUnit()->getCondo()->getPaymentFees();
    }
}
