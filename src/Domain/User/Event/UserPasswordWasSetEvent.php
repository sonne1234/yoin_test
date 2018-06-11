<?php

namespace App\Domain\User\Event;

use App\Domain\DomainEvent;

class UserPasswordWasSetEvent extends DomainEvent
{
    /**
     * @var string
     */
    private $userId;

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
    private $email;

    public function __construct(
        string $userId,
        string $firstName,
        string $lastName,
        string $email
    ) {
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function email(): string
    {
        return $this->email;
    }
}
