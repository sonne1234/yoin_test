<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Platformnotificationrecipient
 *
 * @ORM\Table(name="platformnotificationrecipient", indexes={@ORM\Index(name="idx_94bdbb42ef1a9d84", columns={"notification_id"}), @ORM\Index(name="idx_94bdbb42a76ed395", columns={"user_id"})})
 * @ORM\Entity
 */
class Platformnotificationrecipient
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="guid", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="platformnotificationrecipient_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="readat", type="datetime", nullable=true)
     */
    private $readat;

    /**
     * @var \Useridentity
     *
     * @ORM\ManyToOne(targetEntity="Useridentity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Platformnotification
     *
     * @ORM\ManyToOne(targetEntity="Platformnotification")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="notification_id", referencedColumnName="id")
     * })
     */
    private $notification;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getReadat(): ?\DateTimeInterface
    {
        return $this->readat;
    }

    public function setReadat(?\DateTimeInterface $readat): self
    {
        $this->readat = $readat;

        return $this;
    }

    public function getUser(): ?Useridentity
    {
        return $this->user;
    }

    public function setUser(?Useridentity $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNotification(): ?Platformnotification
    {
        return $this->notification;
    }

    public function setNotification(?Platformnotification $notification): self
    {
        $this->notification = $notification;

        return $this;
    }


}
