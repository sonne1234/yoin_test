<?php

namespace App\Domain\SupportTicket\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\SupportTicket\SupportTicketComment;
use App\Domain\User\Transformer\UserShortInfoTransformer;

class SupportTicketCommentTransformer extends DomainTransformer
{
    public function __construct()
    {
    }

    /**
     * @param SupportTicketComment $entity
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
