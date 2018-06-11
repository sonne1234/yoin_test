<?php

namespace App\Domain\ServiceRequest\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\ServiceRequest\ServiceRequestComment;
use App\Domain\User\Transformer\UserShortInfoTransformer;

class ServiceRequestCommentTransformer extends DomainTransformer
{
    public function __construct()
    {
    }

    /**
     * @param ServiceRequestComment $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'comment' => $entity->getComment(),
            'createdAt' => $entity->getCreatedAt()
                ? $entity->getCreatedAt()->format(\DateTime::ATOM)
                : null,
            'images' => $entity->getImages(),
            'author' => (new UserShortInfoTransformer())->transform($entity->getAuthor()),
            'isRead' => $entity->isRead(),
        ];
    }
}
