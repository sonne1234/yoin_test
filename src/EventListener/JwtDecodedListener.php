<?php

namespace App\EventListener;

use App\Domain\DomainRepository;
use App\Domain\User\Criteria\UserByIdCriteria;
use App\Domain\User\UserIdentity;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;

class JwtDecodedListener
{
    /**
     * @var DomainRepository
     */
    private $userRepository;

    public function setRepo(DomainRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function onTokenDecoded(JWTDecodedEvent $event)
    {
        /** @var UserIdentity $user */
        if ($user = $this->userRepository->getOneByCriteria(
            new UserByIdCriteria((string) ($event->getPayload()['id']) ?? null)
        )) {
            if ($user->isEnabled()) {
                return;
            }
        }

        $event->markAsInvalid();
    }
}
