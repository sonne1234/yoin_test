<?php

namespace App\Domain\Unit\Transformer;

use App\Domain\Condo\Transformer\CondoBuildingTransformer;
use App\Domain\DomainTransformer;
use App\Domain\Unit\Unit;
use App\Domain\User\Transformer\UserShortInfoTransformer;

class UnitShortFeeTransformer extends DomainTransformer
{
    /**
     * @param Unit $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'number' => $entity->getNumber(),
            'condoBuilding' => $entity->getCondoBuilding()
                ? (new CondoBuildingTransformer())->transform($entity->getCondoBuilding())
                : null,
            'firstPrimeUser' => $entity->getFirstPrimeUser()
                ? (new UserShortInfoTransformer(false))->transform($entity->getFirstPrimeUser())
                : null,
            'debt' => $this->transformMoneyToFloat($entity->getMaintenanceFeeDebt()),
            'isCurrentFeePaid' => $entity->isMaintenanceFeePaidForCurrentPeriod(),
        ];
    }
}
