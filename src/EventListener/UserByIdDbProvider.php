<?php

namespace App\EventListener;

use App\Domain\User\Criteria\UserByIdCriteria;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserByIdDbProvider extends UserByEmailDbProvider
{
    public function loadUserByUsername($username)
    {
        if (!$user = $this->userRepository->getOneByCriteria(new UserByIdCriteria($username))) {
            throw new UsernameNotFoundException("User $username not found.");
        }

        return $user;
    }
}
