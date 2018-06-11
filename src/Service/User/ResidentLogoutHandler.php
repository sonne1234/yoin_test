<?php

namespace App\Service\User;

use App\Service\User\Command\ResidentLogoutCommand;
use App\Domain\Device\Criteria\BySessionIdCriteria;
use App\Domain\Device\Device;
use App\Domain\Device\DeviceRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ResidentLogoutHandler
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var JWTManager */
    private $jwtManager;

    /** @var DeviceRepository */
    private $repository;

    /**
     * ResidentLogoutHandler constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param JWTManager            $jwtManager
     * @param DeviceRepository      $repository
     */
    public function __construct(TokenStorageInterface $tokenStorage, JWTManager $jwtManager, DeviceRepository $repository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->jwtManager = $jwtManager;
        $this->repository = $repository;
    }

    public function handle(ResidentLogoutCommand $command)
    {
        $token = $this->tokenStorage->getToken();
        $sessionData = $this->jwtManager->decode($token);
        $sessionId = $sessionData['session_id'];
        /** @var Device $device */
        $device = $this->repository->getOneByCriteria(new BySessionIdCriteria($sessionId));
        if ($device) {
            $device->setSessionId(null);
            $command->getResident()->detachDevice($device);
        }
    }
}
