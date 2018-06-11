<?php

namespace App\Domain\Platform\Event;

use App\Domain\DomainEvent;
use App\Domain\PlatformNotification\AbstractNotification;
use App\Domain\PlatformNotification\NotificationDispatchable;
use App\Domain\PlatformNotification\Type\NewPlatformAdminNotification;
use App\Domain\User\UserIdentity;

class PlatformAdminCreatedEvent extends DomainEvent implements NotificationDispatchable
{
    protected const FIELDS_TO_FILL_NULL_BEFORE_SERIALIZATION = [
        'user',
    ];

    /** @var UserIdentity|null */
    protected $user;

    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $passwordLink;

    /**
     * @var string
     */
    private $role;

    public function __construct(UserIdentity $user, string $passwordLink)
    {
        $this->user = $user;

        $this->userId = $user->getId();
        $this->email = $user->getEmail();
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
        $this->passwordLink = $passwordLink;
        $this->role = $user->getRole();
    }

    /**
     * @return UserIdentity
     */
    public function getUser(): UserIdentity
    {
        return $this->user;
    }

    public function getPlatformNotification(): AbstractNotification
    {
        return (new NewPlatformAdminNotification())
            ->setTargetEntityId($this->user->getId())
            ->setAuthor($this->getCurrentUser())
            ->setMessageArgs([]);
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function passwordLink(): string
    {
        return $this->passwordLink;
    }

    public function role(): string
    {
        return $this->role;
    }
}
