<?php

namespace App\Domain\NotificationGateway\Handler;

use App\Domain\NotificationGateway\Command\SendMassNotificationCommand;
use App\Domain\NotificationGateway\Provider\SnsGatewayProvider;
use Psr\Log\LoggerInterface;

class SendMassNotificationHandler
{
    /** @var SnsGatewayProvider */
    private $snsGatewayProvider;

    /** @var LoggerInterface */
    private $logger;

    /**
     * SendMassNotificationHandler constructor.
     *
     * @param SnsGatewayProvider $snsGatewayProvider
     * @param LoggerInterface    $logger
     */
    public function __construct(SnsGatewayProvider $snsGatewayProvider, LoggerInterface $logger)
    {
        $this->snsGatewayProvider = $snsGatewayProvider;
        $this->logger = $logger;
    }

    public function handle(SendMassNotificationCommand $command)
    {
        $message = $command->getMessage();
        try {
            $this->snsGatewayProvider->topicPublish($command->getTopicArn(), $message);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
        $this->logger->debug('Push '.$message->getKey().' send to topic '.$command->getTopicArn());
    }
}
