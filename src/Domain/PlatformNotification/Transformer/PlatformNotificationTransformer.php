<?php

namespace App\Domain\PlatformNotification\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\PlatformNotification\AbstractNotification;
use App\Domain\User\UserIdentity;

class PlatformNotificationTransformer extends DomainTransformer
{
    /** @var UserIdentity|null */
    private $currentUser;

    public function setCurrentUser(?UserIdentity $currentUser): self
    {
        $this->currentUser = $currentUser;

        return $this;
    }

    /**
     * @param AbstractNotification $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'createdAt' => $entity->getCreatedAt()->format(DATE_ATOM),
            'message_group' => $entity->getGroup(),
            'message_key' => $entity->getMessageKey(),
            'message_args' => $entity->getMessageArgs(),
            'target_entity_id' => $entity->getTargetEntityId(),
        ];
    }
}
