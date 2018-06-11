<?php

namespace App\Domain\ServiceRequest\Transformer;

use App\Domain\Condo\Transformer\CondoBuildingTransformer;
use App\Domain\ServiceRequest\ServiceRequest;
use App\Domain\Unit\Transformer\UnitShortInfoTransformer;
use App\Domain\User\Transformer\UserShortInfoTransformer;

class ServiceRequestAdminTransformer extends ServiceRequestTransformer
{
    /**
     * @param ServiceRequest $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
                'isRead' => $entity->isRead(),
                'isPinned' => $entity->isPinned(),
                'resident' => (new UserShortInfoTransformer(false))->transform($entity->getResident()),
                'unit' => (new UnitShortInfoTransformer())->transform($entity->getResident()->getUnit()),
                'condoBuilding' => (new CondoBuildingTransformer())->transform($entity->getResident()->getUnit()->getCondoBuilding()),
            ] + parent::transformOneEntity($entity);
    }
}
