<?php

namespace App\Domain\PlatformNotification;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class NotificationRecipient.
 *
 * @ORM\Entity()
 * @ORM\Table(name="platformnotificationrecipient")
 */
class NotificationRecipient
{
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
     * @ORM\ManyToOne(targetEntity="App\Domain\PlatformNotification\AbstractNotification", inversedBy="notificationRecipients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $notification;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\User\UserIdentity", inversedBy="notificationsRecipients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $readAt;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param mixed $notification
     *
     * @return NotificationRecipient
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     *
     * @return NotificationRecipient
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getReadAt(): \DateTime
    {
        return $this->readAt;
    }

    /**
     * @return NotificationRecipient
     */
    public function markAsRead(): NotificationRecipient
    {
        $this->readAt = new \DateTime();

        return $this;
    }
}
