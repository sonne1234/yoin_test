<?php

namespace App\Domain\NotificationGateway\Handler;

use App\Domain\NotificationGateway\Command\SendSingleNotificationCommand;
use App\Domain\NotificationGateway\Provider\SnsGatewayProvider;
use App\Domain\Resident\Resident;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Psr\Log\LoggerInterface;

class SendSingleNotificationHandler
{
    /** @var DoctrineUserRepository */
    private $userRepository;

    /** @var SnsGatewayProvider */
    private $snsGatewayProvider;

    /** @var LoggerInterface */
    private $logger;

    /**
     * SendSingleNotificationHandler constructor.
     *
     * @param DoctrineUserRepository $userRepository
     * @param SnsGatewayProvider     $snsGatewayProvider
     * @param LoggerInterface        $logger
     */
    public function __construct(DoctrineUserRepository $userRepository, SnsGatewayProvider $snsGatewayProvider, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->snsGatewayProvider = $snsGatewayProvider;
        $this->logger = $logger;
    }

    public function handle(SendSingleNotificationCommand $command)
    {
        $resident = $this->userRepository->get($command->getRecipientId());
        if ($resident instanceof Resident && $resident->isEnabled() && $resident->getIsNotificationsEnabled()) {
            $message = $command->getMessage();

            foreach ($resident->getDevices() as $device) {
                try {
                    $this->snsGatewayProvider->targetPublish($device->getPlatformEndpoint()->getArn(), $command->getMessage());
                } catch (\Exception $exception) {
                    $this->logger->error($exception->getMessage());
                }
            }
            $this->logger->debug('Push '.$message->getKey().' send to user '.$resident->getId());
        }
    }
}
