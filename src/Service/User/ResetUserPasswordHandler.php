<?php

namespace App\Service\User;

use App\Request\User\ResetUserPasswordRequest;
use App\Domain\DomainRepository;
use App\Domain\User\Criteria\UserByEmailCriteria;
use App\Domain\User\UserIdentity;

class ResetUserPasswordHandler
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

    public function __invoke(ResetUserPasswordRequest $request): void
    {
        /** @var UserIdentity $user */
        if ($user = $this->userRepository->getOneByCriteria(
            new UserByEmailCriteria($request->email)
        )) {
            $user->regenerateResetPasswordLink();
        }
    }
}
