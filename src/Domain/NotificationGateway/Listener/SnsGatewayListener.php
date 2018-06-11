<?php

namespace App\Domain\NotificationGateway\Listener;

use App\Domain\Device\Device;
use App\Domain\Device\Event\DeviceCreatedEvent;
use App\Domain\NotificationGateway\PlatformEndpoint;
use App\Domain\NotificationGateway\Provider\SnsGatewayProvider;
use App\Domain\NotificationGateway\Subscription;
use App\Domain\NotificationGateway\Topic;
use App\Domain\Resident\Event\ResidentCheckedIn;
use App\Domain\Resident\Event\ResidentCheckedOut;
use App\Domain\Resident\Event\ResidentNotificationDisabledEvent;
use App\Domain\Resident\Event\ResidentNotificationEnabledEvent;
use App\Domain\Resident\Resident;
use Aws\Sns\SnsClient;

class SnsGatewayListener
{
    /** @var SnsClient */
    private $snsClient;

    /** @var SnsGatewayProvider */
    private $gatewayProvider;

    private $iosArn;

    private $androidArn;

    /**
     * SnsGatewayListener constructor.
     *
     * @param SnsClient          $snsClient
     * @param SnsGatewayProvider $gatewayProvider
     * @param $iosArn
     * @param $androidArn
     */
    public function __construct(SnsClient $snsClient, SnsGatewayProvider $gatewayProvider, $iosArn, $androidArn)
    {
        $this->snsClient = $snsClient;
        $this->gatewayProvider = $gatewayProvider;
        $this->iosArn = $iosArn;
        $this->androidArn = $androidArn;
    }

    public function createPlatformEndpoint(DeviceCreatedEvent $event)
    {
        $device = $event->getDevice();
        $arn = $this->gatewayProvider->createPlatformEndpoint($device->getPlatform(), $device->getToken());
        $platformEndpoint = (new PlatformEndpoint())->setArn($arn);
        $device->setPlatformEndpoint($platformEndpoint);
    }

    public function createDeviceSubscription(ResidentNotificationEnabledEvent $event)
    {
        $this->updateResidentSubscriptions($event->getResident());
    }

    public function purgeDeviceSubscriptions(ResidentNotificationDisabledEvent $event)
    {
        $devices = $event->getDevice() ? [$event->getDevice()] : $event->getResident()->getDevices();
        foreach ($devices as $device) {
            $this->removeAllDeviceSubscriptions($device);
        }
    }

    public function registerResidentSubscriptions(ResidentCheckedIn $event)
    {
        $this->updateResidentSubscriptions($event->getResident());
    }

    private function updateResidentSubscriptions(Resident $resident)
    {
        $building = $resident->getUnit()->getCondoBuilding();
        $residentsTopic = $building->getResidentsTopic();
        $primeResidentsTopic = $building->getPrimeResidentsTopic();
        if (!$residentsTopic || !$primeResidentsTopic) {
            return;
        }
        foreach ($resident->getDevices() as $device) {
            $this->removeAllDeviceSubscriptions($device);
            $this->subscribeDeviceToTopic($device, $residentsTopic);
            if ($resident->isPrime()) {
                $this->subscribeDeviceToTopic($device, $primeResidentsTopic);
            }
        }
    }

    public function purgeAllUserSubscriptions(ResidentCheckedOut $event)
    {
        $resident = $event->getResident();
        foreach ($resident->getDevices() as $device) {
            $this->removeAllDeviceSubscriptions($device);
        }
    }

    private function subscribeDeviceToTopic(Device $device, Topic $topic)
    {
        if (!$device->getPlatformEndpoint()->hasSubscriptionForTopic($topic)) {
            $this->subscribePlatformEndpoint($device->getPlatformEndpoint(), $topic);
        }
    }

    private function removeAllDeviceSubscriptions(Device $device)
    {
        $platformEndpoint = $device->getPlatformEndpoint();
        foreach ($platformEndpoint->getSubscriptions() as $subscription) {
            $this->unsubscribePlatformEndpoint($subscription);
            $platformEndpoint->removeSubscription($subscription);
        }
    }

    private function subscribePlatformEndpoint(PlatformEndpoint $platformEndpoint, Topic $topic)
    {
        if ($topic->getArn()) {
            $response = $this->snsClient->subscribe([
                'TopicArn' => $topic->getArn(),
                'Protocol' => 'application',
                'Endpoint' => $platformEndpoint->getArn(),
            ]);
            $arn = $response->get('SubscriptionArn');
            $subscription = (new Subscription())
                ->setTopic($topic)
                ->setArn($arn);
            $platformEndpoint->addSubscription($subscription);
        }
    }

    private function unsubscribePlatformEndpoint(Subscription $subscription): void
    {
        $this->snsClient->unsubscribeAsync([
            'SubscriptionArn' => $subscription->getArn(),
        ]);
    }
}
