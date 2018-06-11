<?php

namespace App\Domain\Announcement\Transformer;

use App\Domain\Announcement\Announcement;
use App\Domain\Common\Image;
use App\Domain\Condo\Transformer\CondoBuildingTransformer;
use App\Domain\DomainTransformer;
use App\Domain\Resident\Resident;
use App\Domain\User\UserIdentity;

class AnnouncementTransformer extends DomainTransformer
{
    /** @var UserIdentity|null */
    private $currentUser;

    public function setCurrentUser(?UserIdentity $currentUser): self
    {
        $this->currentUser = $currentUser;

        return $this;
    }

    /**
     * @param Announcement $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'status' => $entity->getStatus(),
            'title' => $entity->getTitle(),
            'description' => $entity->getDescription(),
            'isRead' => $this->currentUser instanceof Resident
                ? $entity->getIsRead($this->currentUser)
                : null,
            'condoBuildings' => $this->currentUser instanceof Resident
                ? null
                : (new CondoBuildingTransformer())->transform($entity->getCondoBuildings()),
            'images' => array_values(array_map(
                function ($image) {
                    /* @var Image $image */
                    return $image->toArray();
                },
                iterator_to_array($entity->getImages())
            )),
            'createdAt' => $entity->getCreatedAt()->format(DATE_ATOM),
            'updatedAt' => $entity->getUpdatedAt()
                ? $entity->getUpdatedAt()->format(DATE_ATOM)
                : null,
        ];
    }
}
