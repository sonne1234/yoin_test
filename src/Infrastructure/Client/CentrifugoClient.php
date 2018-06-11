<?php

namespace App\Infrastructure\Client;

use App\Domain\User\UserIdentity;
use App\Infrastructure\Client\Centrifugo\AbstractMessage;
use App\Infrastructure\Client\Exception\RemoteApiException;
use phpcent\Client;

class CentrifugoClient
{
    const CONDO_CHANNEL_PREFIX = 'condo-';

    const SUPPORT_TICKET_CHANNEL = 'support-ticket';

    /**
     * @var Client
     */
    private $centrifugoClient;

    /**
     * @var string
     */
    private $publicEndpoint;

    public function __construct(string $endpoint, string $secret, string $publicEndpoint)
    {
        $this->centrifugoClient = (new Client($endpoint))->setSecret($secret);
        $this->publicEndpoint = $publicEndpoint;
    }

    public function publish(?string $condoId, string $commentId)
    {
        $key = 'supportTicketId';
        if ($condoId) {
            $key = 'serviceRequestId';
        }

        $message = [
            (object) [
                $key => $commentId,
            ],
        ];

        try {
            $this->centrifugoClient->publish($this->getChannelName($condoId), $message);
        } catch (\Exception $e) {
            throw new RemoteApiException($e->getMessage());
        }
    }

    public function publishMessage(AbstractMessage $message)
    {
        try {
            $this->centrifugoClient->publish($message->getChannelName(), $message->getBody());
        } catch (\Exception $e) {
            throw new RemoteApiException($e->getMessage());
        }
    }

    public function getConnectionData(UserIdentity $user): array
    {
        $timestamp = time();
        $token = $this->centrifugoClient->generateClientToken($user->getId(), $timestamp);

        return [
            'url' => $this->getPublicEndpoint(),
            'userId' => $user->getId(),
            'timestamp' => $timestamp,
            'token' => $token,
        ];
    }

    private function getPublicEndpoint(): string
    {
        return $this->publicEndpoint;
    }

    private function getChannelName(?string $condoId): string
    {
        if ($condoId) {
            return self::CONDO_CHANNEL_PREFIX.$condoId;
        } else {
            return self::SUPPORT_TICKET_CHANNEL;
        }
    }
}
