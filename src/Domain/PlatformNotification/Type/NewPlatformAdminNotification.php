<?php

namespace App\Domain\PlatformNotification\Type;

use App\Domain\PlatformNotification\AbstractNotification;
use App\Domain\PlatformNotification\Criteria\PlatformAdminCriteria;
use App\Infrastructure\Persistence\Doctrine\DoctrinePlatformAdminRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class NewPlatformAdminNotification extends AbstractNotification
{
    public function getGroup(): string
    {
        return parent::GROUP_PLATFORM_ADMIN;
    }

    public function getMessageKey(): string
    {
        return parent::MESSAGE_NEW_PLATFORM_ADMIN;
    }

    public function getRecipientsCriteria()
    {
        return new PlatformAdminCriteria();
    }

    public function getRecipientsRepoClass()
    {
        return DoctrinePlatformAdminRepository::class;
    }
}
