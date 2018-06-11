<?php

namespace App\Domain\PlatformNotification\Type;

use App\Domain\PlatformNotification\AbstractNotification;
use App\Domain\PlatformNotification\Criteria\AccountAdminCriteria;
use App\Infrastructure\Persistence\Doctrine\DoctrineAccountAdminRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class NewCondoAdminNotification extends AbstractNotification
{
    public function getGroup(): string
    {
        return parent::GROUP_CONDO_ADMIN;
    }

    public function getMessageKey(): string
    {
        return parent::MESSAGE_NEW_CONDO_ADMIN;
    }

    public function getRecipientsCriteria()
    {
        return new AccountAdminCriteria($this->getAccount());
    }

    public function getRecipientsRepoClass()
    {
        return DoctrineAccountAdminRepository::class;
    }
}
