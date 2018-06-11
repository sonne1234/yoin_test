<?php

namespace App\Domain\SupportTicket\Transformer;

use App\Domain\Account\Transformer\AccountOnlyNameTransformer;
use App\Domain\Condo\Transformer\CondoBuildingTransformer;
use App\Domain\Condo\Transformer\CondoWithNameTransformer;
use App\Domain\Resident\Resident;
use App\Domain\SupportTicket\SupportTicket;
use App\Domain\Unit\Transformer\UnitShortInfoTransformer;

class SupportTicketAdminTransformer extends SupportTicketTransformer
{
    /**
     * @param SupportTicket $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        $data = [
                'isRead' => $entity->isRead(),
                'condo' => $entity->getCondo() ? (new CondoWithNameTransformer())->transform($entity->getCondo()) : null,
                'account' => $entity->getAccount() ? (new AccountOnlyNameTransformer())->transform($entity->getAccount()) : null,
            ] + parent::transformOneEntity($entity);

        if ($entity->getUser() instanceof Resident) {
            $data['unit'] = (new UnitShortInfoTransformer())->transform($entity->getUser()->getUnit());
            $data['condoBuilding'] = (new CondoBuildingTransformer())->transform($entity->getUser()->getUnit()->getCondoBuilding());
        }

        return $data;
    }
}
