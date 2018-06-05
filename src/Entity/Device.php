<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="device", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_e83b3b8bc8cf43a", columns={"platformendpoint_id"})}, indexes={@ORM\Index(name="idx_e83b3b85f37a13b", columns={"token"}), @ORM\Index(name="idx_e83b3b88012c5b0", columns={"resident_id"})})
 * @ORM\Entity
 */
class Device
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="guid", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="device_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="platform", type="string", length=255, nullable=false)
     */
    private $platform;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=false)
     */
    private $token;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sessionid", type="string", length=255, nullable=true)
     */
    private $sessionid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime", nullable=false)
     */
    private $createdat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedat", type="datetime", nullable=false)
     */
    private $updatedat;

    /**
     * @var \Useridentity
     *
     * @ORM\ManyToOne(targetEntity="Useridentity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resident_id", referencedColumnName="id")
     * })
     */
    private $resident;

    /**
     * @var \Platformendpoint
     *
     * @ORM\ManyToOne(targetEntity="Platformendpoint")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="platformendpoint_id", referencedColumnName="id")
     * })
     */
    private $platformendpoint;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getSessionid(): ?string
    {
        return $this->sessionid;
    }

    public function setSessionid(?string $sessionid): self
    {
        $this->sessionid = $sessionid;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(\DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getUpdatedat(): ?\DateTimeInterface
    {
        return $this->updatedat;
    }

    public function setUpdatedat(\DateTimeInterface $updatedat): self
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    public function getResident(): ?Useridentity
    {
        return $this->resident;
    }

    public function setResident(?Useridentity $resident): self
    {
        $this->resident = $resident;

        return $this;
    }

    public function getPlatformendpoint(): ?Platformendpoint
    {
        return $this->platformendpoint;
    }

    public function setPlatformendpoint(?Platformendpoint $platformendpoint): self
    {
        $this->platformendpoint = $platformendpoint;

        return $this;
    }


}
