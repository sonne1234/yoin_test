<?php

namespace App\Service\User;

use App\Service\AbstractHandler;
use App\Domain\DomainRepository;
use App\Domain\User\Exception\UserCannotActivateHimselfException;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\UserIdentity;

class ActivateUserHandler extends AbstractHandler
{
    /**
     * @var DomainRepository
     */
    private $userRepository;

    public function __construct(
        DomainRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function __invoke(string $userId)
    {
        /** @var UserIdentity $user */
        if (!$user = $this->userRepository->get($userId)) {
            throw new UserNotFoundException();
        }

        if ($this->currentUser && $user === $this->currentUser) {
            throw new UserCannotActivateHimselfException();
        }

        $this->checkAccess([$user]);

        $user->activate();
    }
}
