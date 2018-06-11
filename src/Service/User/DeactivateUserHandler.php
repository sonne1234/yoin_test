<?php

namespace App\Service\User;

use App\Service\AbstractHandler;
use App\Domain\DomainRepository;
use App\Domain\User\Exception\UserCannotDeactivateHimselfException;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\UserIdentity;
use Doctrine\ORM\EntityManager;

class DeactivateUserHandler extends AbstractHandler
{
    /**
     * @var DomainRepository
     */
    private $userRepository;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(
        DomainRepository $userRepository,
        EntityManager $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->em = $entityManager;
    }

    public function __invoke(string $userId)
    {
        $this->em->transactional(function ($em) use ($userId) {
            /** @var UserIdentity $user */
            if (!$user = $this->userRepository->getWithWriteLock($userId)) {
                throw new UserNotFoundException();
            }

            if ($this->currentUser && $user === $this->currentUser) {
                throw new UserCannotDeactivateHimselfException();
            }

            $this->checkAccess([$user]);

            $user->deactivate();
        });
    }
}
