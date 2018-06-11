<?php

namespace App\Domain\PlatformNotification;

use App\Domain\Account\Account;
use App\Domain\Condo\Condo;
use App\Domain\User\UserIdentity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="platformnotification")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "new_account" = "App\Domain\PlatformNotification\Type\NewAccountNotification",
 *     "new_platform_admin" = "App\Domain\PlatformNotification\Type\NewPlatformAdminNotification",
 *     "new_condo" = "App\Domain\PlatformNotification\Type\NewCondoNotification",
 *     "new_condo_admin" = "App\Domain\PlatformNotification\Type\NewCondoAdminNotification",
 *     "new_wfa_booking" = "App\Domain\PlatformNotification\Type\NewBookingWFARequestNotification",
 *     "new_service_request" = "App\Domain\PlatformNotification\Type\NewServiceRequestNotification",
 *     "new_support_ticket" = "App\Domain\PlatformNotification\Type\NewSupportTicketNotification"
 * })
 */
abstract class AbstractNotification
{
    use TimestampableEntity;

    protected const GROUP_ACCOUNT = 'GROUP_ACCOUNT';
    protected const GROUP_PLATFORM_ADMIN = 'GROUP_PLATFORM_ADMIN';
    protected const GROUP_CONDO = 'GROUP_CONDO';
    protected const GROUP_CONDO_ADMIN = 'GROUP_CONDO_ADMIN';
    protected const GROUP_BOOKING = 'GROUP_BOOKING';
    protected const GROUP_SERVICE_REQUEST = 'GROUP_SERVICE_REQUEST';
    protected const GROUP_SUPPORT_TICKET = 'GROUP_SUPPORT_TICKET';

    protected const MESSAGE_NEW_ACCOUNT = 'NEW_ACCOUNT';
    protected const MESSAGE_NEW_PLATFORM_ADMIN = 'NEW_PLATFORM_ADMIN';
    protected const MESSAGE_NEW_CONDO = 'NEW_CONDO';
    protected const MESSAGE_NEW_CONDO_ADMIN = 'NEW_CONDO_ADMIN';
    protected const MESSAGE_NEW_WFA_BOOKING = 'NEW_WFA_BOOKING';
    protected const MESSAGE_NEW_SERVICE_REQUEST = 'NEW_SERVICE_REQUEST';
    protected const MESSAGE_NEW_SUPPORT_TICKET = 'NEW_SUPPORT_TICKET';

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
     * @var array
     * @ORM\Column(type="array", nullable=false)
     */
    protected $messageArgs;

    /**
     * @var UserIdentity[]|ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\PlatformNotification\NotificationRecipient",
     *     mappedBy="notification",
     *     cascade={"all"},
     *     orphanRemoval=true
     * )
     */
    private $notificationRecipients;

    /**
     * @var Condo
     * @ORM\ManyToOne(targetEntity="App\Domain\Condo\Condo", inversedBy="platformNotifications")
     * @ORM\JoinColumn(nullable=true)
     */
    private $condo;

    /**
     * @var Account
     * @ORM\ManyToOne(targetEntity="App\Domain\Account\Account", inversedBy="platformNotifications")
     * @ORM\JoinColumn(nullable=true)
     */
    private $account;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $targetEntityId;

    /**
     * @var UserIdentity
     */
    private $author;

    /**
     * AbstractNotification constructor.
     */
    public function __construct()
    {
        $this->notificationRecipients = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    abstract public function getGroup(): string;

    /**
     * @return string
     */
    abstract public function getMessageKey(): string;

    /**
     * @return array
     */
    public function getMessageArgs(): array
    {
        return $this->messageArgs;
    }

    /**
     * @param array $messageArgs
     *
     * @return AbstractNotification
     */
    public function setMessageArgs(array $messageArgs): AbstractNotification
    {
        $this->messageArgs = $messageArgs;

        return $this;
    }

    public function markAsReadBy(UserIdentity $userIdentity)
    {
        /** @var NotificationRecipient $userNotification */
        $notificationsRecipient = $this->notificationRecipients
            ->matching(
                Criteria::create()
                    ->andWhere(Criteria::expr()->eq('user', $userIdentity))
                    ->andWhere(Criteria::expr()->eq('notification', $this))
            )->first();
        if ($notificationsRecipient) {
            $notificationsRecipient->markAsRead();
        }
    }

    public function addRecipient(UserIdentity $userIdentity)
    {
        $notificationsRecipient = (new NotificationRecipient())
            ->setUser($userIdentity)
            ->setNotification($this);
        $this->notificationRecipients->add(
            $notificationsRecipient
        );
    }

    /**
     * @return UserIdentity[]|ArrayCollection
     */
    public function getNotificationRecipients()
    {
        return $this->notificationRecipients;
    }

    /**
     * @param UserIdentity[]|ArrayCollection $notificationRecipients
     */
    public function setNotificationRecipients($notificationRecipients): void
    {
        $this->notificationRecipients = $notificationRecipients;
    }

    /**
     * @return Condo
     */
    public function getCondo(): ?Condo
    {
        return $this->condo;
    }

    /**
     * @return string
     */
    public function getCondoId(): ?string
    {
        return $this->condo ? $this->condo->getId() : null;
    }

    /**
     * @param Condo $condo
     *
     * @return AbstractNotification
     */
    public function setCondo(?Condo $condo): AbstractNotification
    {
        $this->condo = $condo;

        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     *
     * @return AbstractNotification
     */
    public function setAccount(Account $account): AbstractNotification
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     *
     * @return AbstractNotification
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    public function getRecipientFilter()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getTargetEntityId(): string
    {
        return $this->targetEntityId;
    }

    /**
     * @param string $targetEntityId
     *
     * @return AbstractNotification
     */
    public function setTargetEntityId(string $targetEntityId): AbstractNotification
    {
        $this->targetEntityId = $targetEntityId;

        return $this;
    }

    abstract public function getRecipientsCriteria();

    abstract public function getRecipientsRepoClass();
}
