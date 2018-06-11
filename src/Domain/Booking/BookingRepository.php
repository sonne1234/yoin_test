<?php

namespace App\Domain\Booking;

use App\Domain\Condo\Condo;
use App\Domain\DomainRepository;

interface BookingRepository extends DomainRepository
{
    public function getCountOfWaitingApprovalBookings(?Condo $condo, string $amenityId): int;
}
