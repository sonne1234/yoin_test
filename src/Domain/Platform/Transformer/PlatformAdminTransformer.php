<?php

namespace App\Domain\Platform\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\Platform\PlatformAdmin;
use App\Domain\User\UserIdentity;

class PlatformAdminTransformer extends DomainTransformer
{
    /**
     * @param PlatformAdmin $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        if ($entity->getEmail() === '') {
            $status = 'not_created';
        } elseif (!$entity->getInitializedAt()) {
            $status = 'pending';
        } elseif ($entity->getIsActive()) {
            $status = 'activated';
        } else {
            $status = 'deactivated';
        }

        return [
            'role' => $entity->getRoleName(),
            'id' => $entity->getId(),
            'email' => $entity->getEmail(),
            'firstName' => $entity->getFirstName(),
            'lastName' => $entity->getLastName(),
            'name' => $entity->getName(),
            'image' => $entity->getImage()
                ? $entity->getImage()->toArray()
                : null,
            'phone' => $entity->getPhone(),
            'isActive' => $entity->getIsActive(),
            'createdAt' => $entity->getInitializedAt()
                ? $entity->getInitializedAt()->format(\DateTime::ATOM)
                : $entity->getCreatedAt()->format(\DateTime::ATOM),
            'lastActivatedDeactivatedAt' => ($res = $entity->getLastActivatedDeactivatedAt())
                ? $res->format(\DateTime::ATOM)
                : $res,
            'lastLoginAt' => ($res = $entity->getLastLoginAt())
                ? $res->format(\DateTime::ATOM)
                : $res,
            'lastActiveAt' => ($res = $entity->getLastActiveAt())
                ? $res->format(\DateTime::ATOM)
                : $res,
            'passwordStatusChangedAt' => ($res = $entity->getPasswordStatusChangedAt())
                ? $res->format(\DateTime::ATOM)
                : $res,
            'passwordStatus' => $entity->getPasswordStatus()
                ? UserIdentity::PASSWORD_STATUSES[$entity->getPasswordStatus()]
                : $entity->getPasswordStatus(),
            'status' => $status,
            'isNotificationsEnabled' => $entity->getIsNotificationsEnabled(),
        ];
    }
}
