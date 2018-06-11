<?php

namespace App\Domain\Device;

use App\Domain\NotificationGateway\PlatformEndpoint;
use App\Domain\Resident\Resident;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;

/**
 * @ORM\Entity()
 * @ORM\Table(indexes={@ORM\Index(columns={"token"})})
 * @ORM\HasLifecycleCallbacks()
 */
class Device implements ContainsRecordedMessages
{
    use PrivateMessageRecorderCapabilities;

    public const DEVICE_PLATFORM_IOS = 'ios';
    public const DEVICE_PLATFORM_ANDROID = 'android';

    use TimestampableEntity;

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $platform;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $token;

    /**
     * @var Resident
     * @ORM\ManyToOne(targetEntity="App\Domain\Resident\Resident", inversedBy="devices")
     * @ORM\JoinColumn(nullable=true, name="resident_id", referencedColumnName="id")
     */
    private $resident;

    /**
     * @var PlatformEndpoint
     * @ORM\OneToOne(targetEntity="App\Domain\NotificationGateway\PlatformEndpoint", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $platformEndpoint;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $sessionId;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return Device
     */
    public function setId(string $id): Device
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * @param string $platform
     *
     * @return Device
     */
    public function setPlatform(string $platform): Device
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return Device
     */
    public function setToken(string $token): Device
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return PlatformEndpoint
     */
    public function getPlatformEndpoint(): PlatformEndpoint
    {
        return $this->platformEndpoint;
    }

    /**
     * @param PlatformEndpoint $platformEndpoint
     *
     * @return Device
     */
    public function setPlatformEndpoint(PlatformEndpoint $platformEndpoint): Device
    {
        $this->platformEndpoint = $platformEndpoint;

        return $this;
    }

    /**
     * @return ?Resident
     */
    public function getResident(): ?Resident
    {
        return $this->resident;
    }

    /**
     * @param Resident $resident
     */
    public function setResident(?Resident $resident)
    {
        $this->resident = $resident;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     *
     * @return Device
     */
    public function setSessionId(?string $sessionId): Device
    {
        $this->sessionId = $sessionId;

        return $this;
    }
}
