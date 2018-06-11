<?php

namespace App\Domain\NotificationGateway\Provider;

use App\Domain\Device\Device;
use App\Domain\Device\Exception\UnsupportedPlatformException;
use App\Domain\NotificationGateway\Message;
use Aws\Sns\SnsClient;

class SnsGatewayProvider
{
    /** @var SnsClient */
    private $snsClient;

    private $iosArn;

    private $androidArn;

    private $sandboxMode = false;

    /**
     * SnsGatewayProvider constructor.
     *
     * @param SnsClient $snsClient
     * @param $iosArn
     * @param $androidArn
     */
    public function __construct(SnsClient $snsClient, $iosArn, $androidArn, $sandboxMode)
    {
        $this->snsClient = $snsClient;
        $this->iosArn = $iosArn;
        $this->androidArn = $androidArn;
        $this->sandboxMode = !empty($sandboxMode);
    }

    public function registerSnsTopic($topicName): string
    {
        $response = $this->snsClient->createTopic([
            'Name' => $topicName,
        ]);

        return $response->get('TopicArn');
    }

    public function createPlatformEndpoint($platform, $token): string
    {
        $response = $this->snsClient->createPlatformEndpoint([
            'PlatformApplicationArn' => $this->getPlatformArn($platform),
            'Token' => $token,
        ]);

        return $response->get('EndpointArn');
    }

    public function targetPublish($targetArn, Message $message)
    {
        $snsMessage = $this->buildUniversalMessage($message);
        $this->snsClient->publish([
            'TargetArn' => $targetArn,
            'MessageStructure' => 'json',
            'Message' => $snsMessage,
        ]);
    }

    public function topicPublish($topicArn, Message $message)
    {
        $snsMessage = $this->buildUniversalMessage($message);
        $result = $this->snsClient->publish([
            'TopicArn' => $topicArn,
            'MessageStructure' => 'json',
            'Message' => $snsMessage,
        ]);
    }

    private function getPlatformArn($platform)
    {
        switch ($platform) {
            case Device::DEVICE_PLATFORM_IOS:
                return $this->iosArn;
            case Device::DEVICE_PLATFORM_ANDROID:
                return $this->androidArn;
        }
        throw new UnsupportedPlatformException();
    }

    private function getPlatformGateway($platform)
    {
        if ($platform === Device::DEVICE_PLATFORM_ANDROID) {
            return 'GCM';
        } elseif ($platform === Device::DEVICE_PLATFORM_IOS) {
            return $this->sandboxMode ? 'APNS_SANDBOX' : 'APNS';
        }
        throw new UnsupportedPlatformException();
    }

    private function composeMessage($platform, Message $message)
    {
        if ($platform === Device::DEVICE_PLATFORM_ANDROID) {
            return json_encode(['data' => ['message_key' => $message->getKey(), 'message_args' => $message->getArgs()]]);
        } elseif ($platform === Device::DEVICE_PLATFORM_IOS) {
            return json_encode(['aps' => ['alert' => ['loc-key' => $message->getKey(), 'loc-args' => $message->getArgs()], 'sound' => 'default']]);
        }
        throw new UnsupportedPlatformException();
    }

    private function buildUniversalMessage(Message $message)
    {
        return json_encode([
            'default' => $this->composeMessage(Device::DEVICE_PLATFORM_IOS, $message),
            $this->getPlatformGateway(Device::DEVICE_PLATFORM_IOS) => $this->composeMessage(Device::DEVICE_PLATFORM_IOS, $message),
            $this->getPlatformGateway(Device::DEVICE_PLATFORM_ANDROID) => $this->composeMessage(Device::DEVICE_PLATFORM_ANDROID, $message),
        ]);
    }
}
