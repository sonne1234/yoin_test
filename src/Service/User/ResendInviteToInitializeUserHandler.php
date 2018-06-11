<?php

namespace App\Service\User;

use App\Service\AbstractHandler;
use App\Domain\DomainRepository;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\UserIdentity;

class ResendInviteToInitializeUserHandler extends AbstractHandler
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

        $this->checkAccess([$user]);

        $user->resendInvitationToSetPassword();
    }
}
