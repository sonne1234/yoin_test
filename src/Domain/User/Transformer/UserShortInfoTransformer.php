<?php

namespace App\Domain\User\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\User\UserIdentity;

class UserShortInfoTransformer extends DomainTransformer
{
    private $useImage;

    public function __construct($useImage = true)
    {
        $this->useImage = $useImage;
    }

    /**
     * @param UserIdentity $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
                'id' => $entity->getId(),
                'name' => $entity->getName(),
                'role' => $entity->getRoleName(),
            ] +
            ($this->useImage ? [
                'image' => $entity->getImage() ? $entity->getImage()->toArray() : null,
            ] : []);
    }
}
