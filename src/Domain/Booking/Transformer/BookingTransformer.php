<?php

namespace App\Domain\Booking\Transformer;

use App\Domain\Booking\Booking;
use App\Domain\DomainTransformer;

class BookingTransformer extends DomainTransformer
{
    /**
     * @param Booking $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'status' => $entity->getStatus(),
            'isBookingFree' => $entity->getIsBookingFree(),
            'isNotRefundedYet' => $entity->getIsNotRefundedYet(),
            'paymentAmount' => $entity->getPaymentAmountAsFloat(),
            'paymentPerSlot' => $entity->getPaymentPerSlotAsFloat(),
            'createdAt' => $entity->getCreatedAt()->format(DATE_ATOM),
            'firstBookingDate' => $entity->getFirstBookingDate()->format(DATE_ATOM),
            'placesCount' => $entity->getPlacesCount(),
            'isCancelledByAdmin' => $entity->getIsCancelledByAdmin(),
            'cancellationReason' => $entity->getCancellationReason(),
            'isRefundable' => $entity->getIsRefundable(),
            'cancelledAt' => ($date = $entity->getCancelledAt())
                ? $date->format(DATE_ATOM)
                : $date,
            'paymentAccountId' => $entity->getPaymentAccountId(),
            'isTemporaryPaid' => $entity->getIsTemporaryPaid(),
            'isPaymentTimeExceeded' => $entity->getIsPaymentTimeExceeded(),
            'isCanBeRefundedByAdmin' => $entity->isCanBeRefunded(),
            'isCanBePaidByAdmin' => $entity->isCanBePaid(),
            'isPaidByCard' => $entity->isPaidByCard(),
            'isPaidByCash' => $entity->isPaidByCash(),
            'isRefundedByAdmin' => $entity->isRefundedByAdmin(),
            'isRefundedByPaymentProvider' => $entity->isRefundedByPaymentProvider(),
        ];
    }
}
