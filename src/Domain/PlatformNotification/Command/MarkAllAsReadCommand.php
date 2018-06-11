<?php

namespace App\Domain\PlatformNotification\Command;

use App\Domain\User\UserIdentity;

class MarkAllAsReadCommand
{
    /** @var UserIdentity */
    private $user;

    /**
     * MarkAllAsReadCommand constructor.
     *
     * @param UserIdentity $user
     */
    public function __construct(UserIdentity $user)
    {
        $this->user = $user;
    }

    /**
     * @return UserIdentity
     */
    public function getUser(): UserIdentity
    {
        return $this->user;
    }
}
