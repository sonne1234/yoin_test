<?php

namespace App\Domain\Condo\Transformer;

use App\Domain\Condo\Condo;
use App\Domain\Condo\CondoAdmin;
use App\Domain\Platform\Transformer\PlatformAdminTransformer;

class CondoAdminTransformer extends PlatformAdminTransformer
{
    /**
     * @param CondoAdmin $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'assignedCondos' => array_values(array_map(
                function ($val) {
                    /* @var Condo $val */
                    return [
                        'name' => $val->getName(),
                        'id' => $val->getId(),
                    ];
                },
                iterator_to_array($entity->getCondos())
            )),
            'accountId' => $entity->getAccount()
                ? $entity->getAccount()->getId()
                : null,
        ] + parent::transformOneEntity($entity);
    }
}
