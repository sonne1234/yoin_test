<?php

namespace App\Domain\User\Event;

use App\Domain\DomainEvent;

class UserEmailChangedEvent extends DomainEvent
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $oldEmail;

    /**
     * @var string
     */
    private $newEmail;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var bool
     */
    private $isUserInitialized;

    /**
     * @var string
     */
    private $passwordLink;

    /**
     * @var string
     */
    private $role;

    public function __construct(
        string $userId,
        string $oldEmail,
        string $newEmail,
        string $firstName,
        string $lastName,
        bool $isUserInitialized,
        string $passwordLink,
        string $role
    ) {
        $this->userId = $userId;
        $this->oldEmail = $oldEmail;
        $this->newEmail = $newEmail;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->isUserInitialized = $isUserInitialized;
        $this->passwordLink = $passwordLink;
        $this->role = $role;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function oldEmail(): string
    {
        return $this->oldEmail;
    }

    public function newEmail(): string
    {
        return $this->newEmail;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function isUserInitialized(): bool
    {
        return $this->isUserInitialized;
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
