<?php

namespace App\Domain\Transaction\Transformer;

use App\Domain\Account\Transformer\AccountOnlyNameTransformer;
use App\Domain\Amenity\Transformer\AmenityTransformer;
use App\Domain\Booking\Transformer\BookingTransformer;
use App\Domain\Condo\Transformer\CondoWithNameTransformer;
use App\Domain\DomainTransformer;
use App\Domain\Invoice\Transformer\MaintenanceFeeInvoiceTransformer;
use App\Domain\Resident\Transformer\ResidentShortInfoTransformer;
use App\Domain\Transaction\Transaction;

class TransactionTransformer extends DomainTransformer
{
    /**
     * @param Transaction $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'transactionId' => $entity->getTransactionId(),
            'createdAt' => $entity->getCreatedAt()
                ? $entity->getCreatedAt()->format(\DateTime::ATOM)
                : null,
            'updatedAt' => $entity->getUpdatedAt()
                ? $entity->getUpdatedAt()->format(\DateTime::ATOM)
                : null,
            'serviceName' => $entity->getServiceName(),
            'amount' => $entity->getAmount(),
            'total' => $entity->getTotal(),
            'yoinFee' => $entity->getYoinFee(),
            'date' => $entity->getDate()->format(\DateTime::ATOM),
            'invoice' => $entity->getInvoice()
                ? (new MaintenanceFeeInvoiceTransformer())->transform($entity->getInvoice()) : null,
            'booking' => $entity->getBooking()
                ? (new BookingTransformer())->transform($entity->getBooking()) : null,
            'amenity' => ($entity->getBooking() && $entity->getBooking()->getAmenity())
                ? (new AmenityTransformer())->transform($entity->getBooking()->getAmenity()) : null,
            'resident' => (new ResidentShortInfoTransformer())->transform($entity->getResident()),
            'condo' => (new CondoWithNameTransformer())->transform($entity->getResident()->getUnit()->getCondo()),
            'account' => (new AccountOnlyNameTransformer())
                ->transform($entity->getResident()->getUnit()->getCondo()->getAccount()),
        ];
    }
}
