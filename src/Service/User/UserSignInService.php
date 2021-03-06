<?php

namespace App\Service\User;

use App\Domain\User\UserIdentity;
use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;

class UserSignInService
{
    /**
     * @var JWTManager
     */
    private $jwtManager;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em, JWTManager $jwtManager)
    {
        
        $this->jwtManager = $jwtManager;
        $this->em = $em;
    }

    public function __invoke(UserIdentity $user): array
    {
        $user
            ->regenerateRefreshToken()
            ->setLastLoginAt();

        $this->em->flush();

        return [
            'token' => $this->jwtManager->create($user),
            'user' => $user->getUserTransformer()->transform($user),
            'refreshToken' => $user->getRefreshToken()->getToken(),
        ];
    }
}
