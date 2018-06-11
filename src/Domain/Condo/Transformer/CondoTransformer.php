<?php

namespace App\Domain\Condo\Transformer;

use App\Domain\Condo\Condo;
use App\Domain\DomainTransformer;
use App\Domain\User\Criteria\ActivatedUserCriteria;
use App\Domain\User\Criteria\DeactivatedUserCriteria;
use App\Domain\User\Criteria\NotInitializedUserCriteria;

class CondoTransformer extends DomainTransformer
{
    /**
     * @param Condo $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        $condoAdminTransformer = new CondoAdminTransformer();

        return [
            'id' => $entity->getId(),
            'createdAt' => $entity->getCreatedAt()->format(\DateTime::ATOM),
            'accountId' => $entity->getAccount()
                ? $entity->getAccount()->getId()
                : null,
            'generalData' => (array) $entity->getGeneralData() + [
                'buildings' => (new CondoBuildingTransformer())->transform($entity->getBuildings()),
            ],
            'billingData' => $entity->getBillingData(),
            'whitelabelData' => $entity->getWhitelabelData(),
            'maintenanceData' => $entity->getMaintenanceData()
                ? (new CondoMaintenanceTransformer())->transform($entity->getMaintenanceData())
                : null,
            'paymentData' => $entity->getPaymentData(),
            'isPaymentAvailable'=> $entity->isPaymentAvailable(),
            'pendingMaintenanceFeeThisMonth' => $this->transformMoneyToFloat(
                $entity->getMaintenanceFeePendingThisMonth()
            ),
            'paidMaintenanceFeeThisMonth' => $this->transformMoneyToFloat($entity->getMaintenanceFeePaidThisMonth()),
            'paidMaintenanceFeeTotal' => $this->transformMoneyToFloat($entity->getMaintenanceFeeTotalPaid()),
            'debtMaintenanceFeeTotal' => $this->transformMoneyToFloat($entity->getMaintenanceFeeDebt()),
            'admins' => [
                'pending' => $condoAdminTransformer->transform(
                    $entity->getAdminsByCriteria(new NotInitializedUserCriteria())->toArray()
                ),
                'activated' => $condoAdminTransformer->transform(
                    $entity->getAdminsByCriteria(new ActivatedUserCriteria())->toArray()
                ),
                'deactivated' => $condoAdminTransformer->transform(
                    $entity->getAdminsByCriteria(new DeactivatedUserCriteria())->toArray()
                ),
            ],
        ];
    }
}
