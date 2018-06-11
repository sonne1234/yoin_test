<?php

namespace App\Domain\Platform;

use App\Domain\Common\Image;
use App\Domain\DomainEventPublisher;
use App\Domain\DomainTransformer;
use App\Domain\Platform\Event\PlatformAdminCreatedEvent;
use App\Domain\Platform\Transformer\PlatformAdminTransformer;
use App\Domain\User\Exception\UserEmailIsEmptyException;
use App\Domain\User\UserIdentity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class PlatformAdmin extends UserIdentity
{
    public function __construct(
        string $email,
        string $password,
        string $firstName,
        string $lastName,
        ?Image $image,
        string $phone
    ) {
        parent::__construct($email, $password, UserIdentity::ROLE_PLATFORM_ADMIN, $image, $firstName, $lastName, $phone);

        if (is_null($this->email)) {
            throw new UserEmailIsEmptyException();
        }

        DomainEventPublisher::instance()->publish(
            new PlatformAdminCreatedEvent(
                $this,
                $this->initialPasswordLink
            )
        );
    }

    public function getUserTransformer(): DomainTransformer
    {
        return new PlatformAdminTransformer();
    }
}
