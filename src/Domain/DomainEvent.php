<?php

namespace App\Domain;

use App\Domain\User\UserIdentity;

abstract class DomainEvent
{
    protected const FIELDS_TO_FILL_NULL_BEFORE_SERIALIZATION = [];

    /** @var string */
    private $currentUserId;

    /** @var UserIdentity */
    private $currentUser;

    public function setCurrentUser(?UserIdentity $userIdentity): self
    {
        $this->currentUser = $userIdentity ?? null;
        $this->currentUserId = $userIdentity ? $userIdentity->getId() : null;

        return $this;
    }

    public function getCurrentUser(): ?UserIdentity
    {
        return $this->currentUser;
    }

    /**
     * @return string
     */
    public function getCurrentUserId(): ?string
    {
        return $this->currentUserId;
    }

    public function prepareForSerialization(): self
    {
        foreach (static::FIELDS_TO_FILL_NULL_BEFORE_SERIALIZATION as $field) {
            $this->$$field = null;
        }

        $this->setCurrentUser(null);

        return $this;
    }
}
