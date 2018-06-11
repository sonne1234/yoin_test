<?php

namespace App\Domain\Invoice\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\Invoice\MaintenanceFeeInvoice;

class MaintenanceFeeInvoiceTransformer extends DomainTransformer
{
    /**
     * @param MaintenanceFeeInvoice $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'createdAt' => $entity->getCreatedAt()
                ? $entity->getCreatedAt()->format(\DateTime::ATOM)
                : null,
            'updatedAt' => $entity->getUpdatedAt()
                ? $entity->getUpdatedAt()->format(\DateTime::ATOM)
                : null,
            'payPeriod' => $entity->getPayPeriod()->format('Y-m'),
            'amount' => $this->transformMoneyToFloat($entity->getAmount()),
            'isPaid' => $entity->isPaid(),
            'isPaidByCash' => $entity->isPaidByCash(),
            'paidAt' => $entity->getPaidAt()
                ? $entity->getPaidAt()->format(\DateTime::ATOM)
                : null,
            'paymentAccountId' => $entity->getUnit()->getCondo()->getPaymentAccountId(),
        ];
    }
}
