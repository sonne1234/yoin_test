<?php

namespace App\Service\User;

use App\Request\User\SetUserPasswordRequest;
use App\Domain\DomainRepository;
use App\Domain\User\Criteria\UserByInitialPasswordLinkCriteria;
use App\Domain\User\Criteria\UserByResetPasswordLinkCriteria;
use App\Domain\User\Exception\UserCannotSetPreviousUsedPasswordException;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\UserIdentity;

class SetUserPasswordHandler
{
    /**
     * @var DomainRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    public function __construct(
        UserPasswordEncoder $encoder,
        DomainRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $encoder;
    }

    public function __invoke(SetUserPasswordRequest $request): void
    {
        $criterias = [
            SetUserPasswordRequest::TYPE_INITIALIZE => UserByInitialPasswordLinkCriteria::class,
            SetUserPasswordRequest::TYPE_RESET => UserByResetPasswordLinkCriteria::class,
        ];

        $criteria = null;
        foreach (SetUserPasswordRequest::TYPES as $type) {
            if ($request->has($type)) {
                $criteria = new $criterias[$type]($request->get($type));
                break;
            }
        }

        /** @var UserIdentity $user */
        if (!$user = $this->userRepository->getOneByCriteria($criteria)) {
            throw new UserNotFoundException();
        }

        if ($this->passwordEncoder->isPasswordValid(
            $user->getPassword(),
            $request->password
        )) {
            throw new UserCannotSetPreviousUsedPasswordException();
        }

        $user->setPassword(
            $this->passwordEncoder->encode($request->password)
        );
    }
}
