<?php

namespace App\Domain\Account;

use App\Domain\Account\Event\AccountCreatedEvent;
use App\Domain\Account\Exception\AccountAdminNotFoundException;
use App\Domain\Common\Image;
use App\Domain\Condo\Condo;
use App\Domain\Condo\CondoAdmin;
use App\Domain\Condo\Exception\CondoAdminNotFoundException;
use App\Domain\Condo\Exception\CondoNotFoundException;
use App\Domain\DomainCriteria;
use App\Domain\DomainEventPublisher;
use App\Domain\GetEntityByIdInCollectionTrait;
use App\Domain\ImageRemoverEventTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Account
{
    use
        GetEntityByIdInCollectionTrait,
        ImageRemoverEventTrait;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var AccountBillingData
     * @ORM\Embedded(class="AccountBillingData")
     */
    protected $billingData;

    /**
     * @var AccountGeneralData
     * @ORM\Embedded(class="AccountGeneralData")
     */
    protected $generalData;

    /**
     * @var Image|null
     * @ORM\OneToOne(targetEntity="App\Domain\Common\Image")
     */
    protected $image;

    /**
     * @var ArrayCollection AccountAdmin[]
     * @ORM\OneToMany(
     *     targetEntity="AccountAdmin",
     *     mappedBy="account",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *   )
     */
    private $accountAdmins;

    /**
     * @var ArrayCollection Condo[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Condo\Condo",
     *     mappedBy="account",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *   )
     */
    private $condos;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isAccountInfoFilled = false;

    /**
     * @var ArrayCollection CondoAdmin[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Condo\CondoAdmin",
     *     mappedBy="account",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *   )
     */
    private $condoAdmins;

    public function __construct(
        AccountAdmin $accountAdmin,
        AccountBillingData $billingData,
        AccountGeneralData $generalData
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new \DateTime();
        $this->accountAdmins = new ArrayCollection();
        $this->condos = new ArrayCollection();
        $this->condoAdmins = new ArrayCollection();

        $this->setAccountGeneralData($generalData);
        $this->setAccountBillingData($billingData);
        $this->addAccountAdmin($accountAdmin);

        DomainEventPublisher::instance()->publish(
            new AccountCreatedEvent($this)
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function addAccountAdmin(AccountAdmin $accountAdmin): self
    {
        $this->accountAdmins->add(
            $accountAdmin->setAccount($this)
        );

        return $this;
    }

    public function getAccountAdmin(string $id, bool $throwExceptionIfNotFound = true): ?AccountAdmin
    {
        $res = $this
            ->accountAdmins
            ->matching(
                Criteria::create()->where(
                    Criteria::expr()->eq('id', $id)
                )
            );

        if ($throwExceptionIfNotFound && !$res->count()) {
            throw new AccountAdminNotFoundException();
        }

        return $res->count()
            ? $res->first()
            : null;
    }

    public function getCondoAdmin(string $id, bool $throwExceptionIfNotFound = true): ?CondoAdmin
    {
        $res = $this
            ->condoAdmins
            ->matching(
                Criteria::create()->where(
                    Criteria::expr()->eq('id', $id)
                )
            );

        if ($throwExceptionIfNotFound && !$res->count()) {
            throw new CondoAdminNotFoundException();
        }

        return $res->count()
            ? $res->first()
            : null;
    }

    /**
     * @param DomainCriteria $criteria
     *
     * @return Collection|AccountAdmin[]
     */
    public function getAccountAdminsByCriteria(DomainCriteria $criteria): Collection
    {
        return $this->accountAdmins->matching($criteria->create());
    }

    /**
     * @param DomainCriteria $criteria
     *
     * @return Collection|CondoAdmin[]
     */
    public function getCondoAdminsByCriteria(DomainCriteria $criteria): Collection
    {
        return $this->condoAdmins->matching($criteria->create());
    }

    public function getPrimaryAccountAdmin(): ?AccountAdmin
    {
        $res = $this
            ->accountAdmins
            ->matching(Criteria::create()->where(
                Criteria::expr()->eq('isPrimary', true)
            ));

        return $res->count()
            ? $res->first()
            : null;
    }

    public function getAccountAdminsCount(): int
    {
        return $this->accountAdmins->count();
    }

    public function getAccountCompanyName(): string
    {
        return $this->generalData->getCompanyName();
    }

    public function getAccountLogoUrl(): ?array
    {
        return$this->image
            ? $this->image->toArray()
            : null;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setAccountBillingData(AccountBillingData $accountBillingData): self
    {
        $this->billingData = $accountBillingData;
        $this->verifyIsAccountInfoFilled();

        return $this;
    }

    public function setAccountGeneralData(AccountGeneralData $accountGeneralData): self
    {
        $this->generalData = $accountGeneralData;
        $this->image = $accountGeneralData->getImage();
        $this->verifyIsAccountInfoFilled();

        return $this;
    }

    public function getBillingData(): array
    {
        return $this->billingData->toArray();
    }

    public function getGeneralData(): array
    {
        $res = $this->generalData->toArray();
        $res += ['logoUrl' => $this->image
            ? $this->image->toArray()
            : null,
        ];

        return $res;
    }

    public function isAccountInfoFilled(): bool
    {
        return $this->isAccountInfoFilled;
    }

    public function isAccountGeneralInfoFilled(): bool
    {
        return $this->generalData ? $this->generalData->isDataFilled() : false;
    }

    public function isAccountBillingInfoFilled(): bool
    {
        return $this->billingData ? $this->billingData->isDataFilled() : false;
    }

    public function addCondo(Condo $condo): self
    {
        $this->condos->add(
            $condo->setAccount($this)
        );

        return $this;
    }

    public function addCondoAdmin(CondoAdmin $condoAdmin): self
    {
        $this->condoAdmins->add(
            $condoAdmin->setAccount($this)
        );

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return clone $this->createdAt;
    }

    private function verifyIsAccountInfoFilled(): self
    {
        if ($this->isAccountGeneralInfoFilled() && $this->isAccountBillingInfoFilled()) {
            $this->isAccountInfoFilled = true;
        }

        return $this;
    }

    public function getCondo(string $id, bool $throwExceptionIfNotFound = true): ?Condo
    {
        return $this->getEntityByIdInCollection(
            $id,
            $this->condos,
            $throwExceptionIfNotFound,
            CondoNotFoundException::class
        );
    }

    public function getCondosCount(): int
    {
        return $this->condos->count();
    }

    /**
     * @return Collection|Condo[]
     */
    public function getCondos(): Collection
    {
        return $this->condos;
    }
}
