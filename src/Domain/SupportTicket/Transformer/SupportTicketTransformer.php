<?php

namespace App\Domain\SupportTicket\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\SupportTicket\SupportTicket;
use App\Domain\User\Transformer\UserShortInfoTransformer;

class SupportTicketTransformer extends DomainTransformer
{
    /**
     * @param SupportTicket $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'category' => $entity->getCategory(),
            'subCategory' => $entity->getSubCategory(),
            'description' => $entity->getDescription(),
            'createdAt' => $entity->getCreatedAt()
                ? $entity->getCreatedAt()->format(\DateTime::ATOM)
                : null,
            'updatedAt' => $entity->getUpdatedAt()
                ? $entity->getUpdatedAt()->format(\DateTime::ATOM)
                : null,
            'images' => $entity->getImages(),
            'createdBy' => (new UserShortInfoTransformer(false))->transform($entity->getUser()),
            'hasUnreadComments' => $entity->hasUnreadComments(),
        ];
    }
}
