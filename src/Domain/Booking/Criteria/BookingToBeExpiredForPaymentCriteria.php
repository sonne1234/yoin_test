<?php

namespace App\Domain\Booking\Criteria;

use App\Domain\Booking\Booking;
use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class BookingToBeExpiredForPaymentCriteria implements DomainCriteria
{
    private const LIMIT_MINUTES = 15;

    public function create(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('isPaymentTimeExceeded', false))
            ->andWhere(Criteria::expr()->eq('isCreatedByResident', true))
            ->andWhere(Criteria::expr()->eq('status', Booking::STATUS_WAITING_PAYMENT))
            ->andWhere(Criteria::expr()->lte(
                'createdAt',
                (new \DateTime())->modify('-'.self::LIMIT_MINUTES.' minutes')
            ));
    }
}
