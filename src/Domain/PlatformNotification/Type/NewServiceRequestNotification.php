<?php

namespace App\Domain\PlatformNotification\Type;

use App\Domain\Condo\CondoAdmin;
use App\Domain\PlatformNotification\AbstractNotification;
use App\Domain\PlatformNotification\Criteria\CondoAdminCriteria;
use App\Infrastructure\Persistence\Doctrine\DoctrineCondoAdminRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class NewServiceRequestNotification extends AbstractNotification
{
    public function getGroup(): string
    {
        return parent::GROUP_SERVICE_REQUEST;
    }

    public function getMessageKey(): string
    {
        return parent::MESSAGE_NEW_SERVICE_REQUEST;
    }

    public function getRecipientsCriteria()
    {
        return new CondoAdminCriteria();
    }

    public function getRecipientsRepoClass()
    {
        return DoctrineCondoAdminRepository::class;
    }

    public function getRecipientFilter()
    {
        return function ($element) {
            /* @var $element CondoAdmin */
            return $element->getCondos()->contains($this->getCondo());
        };
    }
}
