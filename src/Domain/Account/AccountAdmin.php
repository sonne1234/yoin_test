<?php

namespace App\Domain\Account;

use App\Domain\Account\Event\AccountAdminCreatedEvent;
use App\Domain\Account\Exception\DeactivatePrimaryAccountAdminException;
use App\Domain\Account\Transformer\AccountAdminTransformer;
use App\Domain\Common\Image;
use App\Domain\DomainEventPublisher;
use App\Domain\DomainTransformer;
use App\Domain\User\Exception\UserEmailIsEmptyException;
use App\Domain\User\UserIdentity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class AccountAdmin extends UserIdentity
{
    /**
     * @var Account|null
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="accountAdmins")
     */
    private $account;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isPrimary = false;

    public function __construct(
        string $email,
        string $password,
        string $firstName,
        string $lastName,
        ?Image $image,
        string $phone
    ) {
        parent::__construct($email, $password, UserIdentity::ROLE_ACCOUNT_ADMIN, $image, $firstName, $lastName, $phone);

        if (is_null($this->email)) {
            throw new UserEmailIsEmptyException();
        }

        DomainEventPublisher::instance()->publish(
            new AccountAdminCreatedEvent(
                $this,
                $this->initialPasswordLink
            )
        );
    }

    public function getUserTransformer(): DomainTransformer
    {
        return new AccountAdminTransformer();
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function markAsPrimary(): self
    {
        $this->isPrimary = true;

        return $this;
    }

    public function getIsPrimary(): bool
    {
        return $this->isPrimary;
    }

    public function deactivate(): UserIdentity
    {
        if ($this->isPrimary) {
            throw new DeactivatePrimaryAccountAdminException();
        }

        return parent::deactivate();
    }
}
