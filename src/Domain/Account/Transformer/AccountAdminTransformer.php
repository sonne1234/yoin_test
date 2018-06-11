<?php

namespace App\Domain\Account\Transformer;

use App\Domain\Account\AccountAdmin;
use App\Domain\Platform\Transformer\PlatformAdminTransformer;

class AccountAdminTransformer extends PlatformAdminTransformer
{
    /**
     * @param AccountAdmin $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'accountId' => $entity->getAccount()
                ? $entity->getAccount()->getId()
                : null,
            'isPrimaryAccountAdmin' => $entity->getIsPrimary(),
            'isAccountInfoFilled' => $entity->getAccount()
                ? $entity->getAccount()->isAccountInfoFilled()
                : false,
        ] + parent::transformOneEntity($entity);
    }
}
