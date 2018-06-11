<?php

namespace App\Service\User;

use App\Service\AbstractHandler;
use App\Domain\Account\Transformer\AccountAdminTransformer;
use App\Domain\Condo\Transformer\CondoAdminTransformer;
use App\Domain\DomainRepository;
use App\Domain\Platform\Transformer\PlatformAdminTransformer;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Transformer\UserShortInfoTransformer;
use App\Domain\User\UserIdentity;

class ViewUserHandler extends AbstractHandler
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

    public function __invoke(string $userId): array
    {
        if (!$user = $this->userRepository->get($userId)) {
            throw new UserNotFoundException();
        }

        /*
         * @var $user UserIdentity
         */
        $this->checkAccess([$user]);

        $role = $user->getRoles()[0];

        switch ($role) {
            case UserIdentity::ROLE_PLATFORM_ADMIN:
                $transformer = new PlatformAdminTransformer();
                break;
            case UserIdentity::ROLE_ACCOUNT_ADMIN:
                $transformer = new AccountAdminTransformer();
                break;
            case UserIdentity::ROLE_CONDO_ADMIN:
                $transformer = new CondoAdminTransformer();
                break;
            default:
                $transformer = new UserShortInfoTransformer(true);
        }

        return $transformer->transform($user);
    }
}
