<?php

namespace App\EventListener;

use App\Domain\DomainRepository;
use App\Domain\User\Criteria\UserByEmailCriteria;
use App\Domain\User\UserIdentity;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserByEmailDbProvider implements UserProviderInterface
{
    /**
     * @var DomainRepository
     */
    protected $userRepository;

    public function __construct(
        DomainRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername($username)
    {
        if (!$user = $this->userRepository->getOneByCriteria(new UserByEmailCriteria($username))) {
            throw new UsernameNotFoundException("User $username not found.");
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass($class)
    {
        return (new \ReflectionClass($class))->isSubclassOf(UserIdentity::class);
    }
}
