<?php

namespace App\Domain\ServiceRequest\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\ServiceRequest\ServiceRequest;

class ServiceRequestTransformer extends DomainTransformer
{
    /**
     * @param ServiceRequest $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'category' => $entity->getCategory(),
            'title' => $entity->getTitle(),
            'description' => $entity->getDescription(),
            'createdAt' => $entity->getCreatedAt()
                ? $entity->getCreatedAt()->format(\DateTime::ATOM)
                : null,
            'updatedAt' => $entity->getUpdatedAt()
                ? $entity->getUpdatedAt()->format(\DateTime::ATOM)
                : null,
            'images' => $entity->getImages(),
            'hasUnreadComments' => $entity->hasUnreadComments(),
        ];
    }
}
