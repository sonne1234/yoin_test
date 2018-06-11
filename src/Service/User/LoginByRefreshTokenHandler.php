<?php

namespace App\Service\User;

use App\Request\User\LoginByRefreshTokenRequest;
use App\Domain\DomainRepository;
use App\Domain\User\Criteria\UserByRefreshTokenCriteria;
use App\Domain\User\Exception\UserRefreshTokenExpiredException;
use App\Domain\User\Exception\UserWithRefreshTokenNotFoundException;
use App\Domain\User\UserIdentity;

class LoginByRefreshTokenHandler
{
    /**
     * @var DomainRepository
     */
    private $userRepository;

    /**
     * @var UserSignInService
     */
    private $userSignInService;

    public function __construct(
        DomainRepository $userRepository,
        UserSignInService $userSignInService
    ) {
        $this->userRepository = $userRepository;
        $this->userSignInService = $userSignInService;
    }

    public function __invoke(LoginByRefreshTokenRequest $request): array
    {
        /** @var UserIdentity $user */
        if (!$user = $this->userRepository->getOneByCriteria(
            new UserByRefreshTokenCriteria($request->refreshToken)
        )) {
            throw new UserWithRefreshTokenNotFoundException();
        }

        if (!$user->getRefreshToken()->isValid()) {
            throw new UserRefreshTokenExpiredException();
        }

        return ($this->userSignInService)($user);
    }
}
