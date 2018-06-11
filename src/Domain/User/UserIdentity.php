<?php

namespace App\Domain\User;

use App\Domain\Common\Image;
use App\Domain\DomainEventPublisher;
use App\Domain\DomainTransformer;
use App\Domain\User\Event\UserEmailChangedEvent;
use App\Domain\User\Event\UserInitializedEvent;
use App\Domain\User\Event\UserInviteToInitializeResentEvent;
use App\Domain\User\Event\UserPasswordWasSetEvent;
use App\Domain\User\Event\UserResetPasswordLinkRegeneratedEvent;
use App\Domain\User\Exception\UserAlreadySetInitialPasswordException;
use App\Domain\User\Exception\UserEmailIsEmptyException;
use App\Domain\User\Exception\UserHasNotSetInitialPasswordYetException;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"isprimary", "account_id"}, options={"where": "isprimary=true"}),
 * })
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="user_type", type="string")
 * @ORM\DiscriminatorMap({
 *     "account_admin" = "App\Domain\Account\AccountAdmin",
 *     "platform_admin" = "App\Domain\Platform\PlatformAdmin",
 *     "condo_admin" = "App\Domain\Condo\CondoAdmin",
 *     "resident" = "App\Domain\Resident\Resident"
 * })
 */
abstract class UserIdentity implements AdvancedUserInterface
{
    const TABLE_NAME = 'useridentity';

    const ROLE_PLATFORM_ADMIN = 'ROLE_PLATFORM_ADMIN';
    const ROLE_ACCOUNT_ADMIN = 'ROLE_ACCOUNT_ADMIN';
    const ROLE_CONDO_ADMIN = 'ROLE_CONDO_ADMIN';
    const ROLE_RESIDENT = 'ROLE_RESIDENT';

    const PASSWORD_STATUS_SENT = 1;
    const PASSWORD_STATUS_RESENT = 2;
    const PASSWORD_STATUSES = [
        self::PASSWORD_STATUS_SENT => 'Request to set password sent',
        self::PASSWORD_STATUS_RESENT => 'Request to set password resent',
    ];

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $password;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $lastName;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $isActive = false;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $role;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $initializedAt;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastActivatedDeactivatedAt;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastLoginAt;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastActiveAt;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $passwordStatus;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $passwordStatusChangedAt;

    /**
     * @var string|null
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    protected $initialPasswordLink;

    /**
     * @var string|null
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    protected $resetPasswordLink;

    /**
     * @var UserRefreshToken
     * @ORM\Embedded(class="UserRefreshToken")
     */
    protected $refreshToken;

    /**
     * @var Image|null
     * @ORM\OneToOne(targetEntity="App\Domain\Common\Image")
     * @ORM\JoinColumn(nullable=true, unique=false, onDelete="SET NULL")
     */
    protected $image;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $phone;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $isNotificationsEnabled = true;

    public function __construct(
        string $email,
        string $password,
        string $role,
        ?Image $image,
        string $firstName,
        string $lastName,
        string $phone
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->role = $role;
        $this->email = ($email = mb_strtolower(trim($email))) === ''
            ? null
            : $email;
        $this->password = $password;
        $this->image = $image;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->createdAt = new \DateTime();

        if (!is_null($this->email)) {
            $this->passwordStatus = self::PASSWORD_STATUS_SENT;
            $this->passwordStatusChangedAt = new \DateTime();
        }

        $this->regenerateInitialPasswordLink();
        $this->regenerateRefreshToken();
    }

    abstract public function getUserTransformer(): DomainTransformer;

    public function getId(): string
    {
        return $this->id;
    }

    public function getInitialPasswordLink(): ?string
    {
        return $this->initialPasswordLink;
    }

    public function getResetPasswordLink(): ?string
    {
        return $this->resetPasswordLink;
    }

    public function setPassword(string $password): self
    {
        if (is_null($this->email)) {
            throw new UserEmailIsEmptyException();
        }

        $this->password = $password;
        $this->initialPasswordLink = null;
        $this->resetPasswordLink = null;
        $this->passwordStatus = null;
        $this->passwordStatusChangedAt = null;

        if ($this->initializedAt) {
            DomainEventPublisher::instance()->publish(
                new UserPasswordWasSetEvent(
                    $this->id,
                    $this->firstName,
                    $this->lastName,
                    (string) $this->email
                )
            );
        } else {
            $this->initializedAt = new \DateTime();
            $this->activate();
            DomainEventPublisher::instance()->publish(
                new UserInitializedEvent($this->id)
            );
        }

        return $this;
    }

    private function setEmail(string $newEmail): self
    {
        $newEmail = ($newEmail = mb_strtolower(trim($newEmail))) === ''
            ? null
            : $newEmail;
        $oldEmail = $this->email;

        if (!is_null($oldEmail) && is_null($newEmail)) {
            throw new UserEmailIsEmptyException();
        }

        if ($newEmail !== $oldEmail) {
            $this->email = $newEmail;

            if (!$this->initializedAt) {
                // regenerate link to set initial password
                $this->regenerateInitialPasswordLink();
            }

            if (is_null($oldEmail)) {
                $this->passwordStatus = self::PASSWORD_STATUS_SENT;
                $this->passwordStatusChangedAt = new \DateTime();
            }

            DomainEventPublisher::instance()->publish(
                new UserEmailChangedEvent(
                    $this->id,
                    (string) $oldEmail,
                    (string) $newEmail,
                    $this->firstName,
                    $this->lastName,
                    (bool) $this->initializedAt,
                    (string) $this->initialPasswordLink,
                    $this->role
                )
            );
        }

        return $this;
    }

    public function activate(): self
    {
        if (!$this->initializedAt) {
            throw new UserHasNotSetInitialPasswordYetException();
        }

        if (!$this->isActive) {
            $this->lastActivatedDeactivatedAt = new \DateTime();
            $this->isActive = true;
        }

        return $this;
    }

    public function deactivate(): self
    {
        if (!$this->initializedAt) {
            throw new UserHasNotSetInitialPasswordYetException();
        }

        if ($this->isActive) {
            $this->lastActivatedDeactivatedAt = new \DateTime();
            $this->isActive = false;
        }

        return $this;
    }

    public function isAccountNonExpired(): bool
    {
        return true;
    }

    public function isAccountNonLocked(): bool
    {
        return true;
    }

    public function isCredentialsNonExpired(): bool
    {
        return true;
    }

    public function isEnabled(): bool
    {
        return $this->initializedAt && $this->isActive;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getRoleName(): string
    {
        return substr(strchr(strtolower($this->getRoles()[0]), '_'), 1);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials()
    {
    }

    public function getRefreshToken(): UserRefreshToken
    {
        return $this->refreshToken;
    }

    public function regenerateRefreshToken(): self
    {
        $this->refreshToken = new UserRefreshToken();

        return $this;
    }

    public function setLastLoginAt(): self
    {
        $this->lastLoginAt = new \DateTime();

        return $this;
    }

    public function getPasswordStatus(): ?int
    {
        return $this->passwordStatus;
    }

    public function getPasswordStatusChangedAt(): ?\DateTime
    {
        return $this->passwordStatusChangedAt
            ? clone $this->passwordStatusChangedAt
            : $this->passwordStatusChangedAt;
    }

    public function getLastActivatedDeactivatedAt(): ?\DateTime
    {
        return $this->lastActivatedDeactivatedAt
            ? clone $this->lastActivatedDeactivatedAt
            : $this->lastActivatedDeactivatedAt;
    }

    public function getEmail(): string
    {
        return (string) $this->email;
    }

    public function getLastLoginAt(): ?\DateTime
    {
        return $this->lastLoginAt
            ? clone $this->lastLoginAt
            : $this->lastLoginAt;
    }

    public function getLastActiveAt(): ?\DateTime
    {
        return $this->lastActiveAt
            ? clone $this->lastActiveAt
            : $this->lastActiveAt;
    }

    public function setLastActiveAt(): self
    {
        $this->lastActiveAt = new \DateTime();

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function resendInvitationToSetPassword(): self
    {
        if (is_null($this->email)) {
            throw new UserEmailIsEmptyException();
        }

        if ($this->initializedAt) {
            throw new UserAlreadySetInitialPasswordException();
        }

        $this->passwordStatus = self::PASSWORD_STATUS_RESENT;
        $this->passwordStatusChangedAt = new \DateTime();

        DomainEventPublisher::instance()->publish(
            new UserInviteToInitializeResentEvent(
                $this,
                $this->initialPasswordLink
            )
        );

        return $this;
    }

    public function getInitializedAt(): ?\DateTime
    {
        return $this->initializedAt ?
            clone $this->initializedAt
            : null;
    }

    public function updateInfo(string $email, string $firstName, string $lastName, ?Image $image, string $phone): self
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->image = $image;
        $this->phone = $phone;
        $this->setEmail($email);

        return $this;
    }

    public function getName(): string
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

    public function getCreatedAt(): \DateTime
    {
        return clone $this->createdAt;
    }

    public function regenerateResetPasswordLink(): self
    {
        $this->resetPasswordLink = $this->generateHash();

        DomainEventPublisher::instance()->publish(
            new UserResetPasswordLinkRegeneratedEvent(
                $this,
                $this->resetPasswordLink
            )
        );

        return $this;
    }

    public function getIsNotificationsEnabled(): bool
    {
        return $this->isNotificationsEnabled;
    }

    public function disableNotifications(): self
    {
        $this->isNotificationsEnabled = false;

        return $this;
    }

    public function enableNotifications(): self
    {
        $this->isNotificationsEnabled = true;

        return $this;
    }

    private function regenerateInitialPasswordLink(): self
    {
        $this->initialPasswordLink = $this->generateHash();

        return $this;
    }

    private function generateHash(): string
    {
        $strong = true;

        return md5((string) $this->email).bin2hex(openssl_random_pseudo_bytes(30, $strong));
    }
}
