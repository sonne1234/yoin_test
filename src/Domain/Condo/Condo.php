<?php

namespace App\Domain\Condo;

use App\Domain\Account\Account;
use App\Domain\Amenity\Amenity;
use App\Domain\Announcement\Announcement;
use App\Domain\Common\Image;
use App\Domain\Condo\Event\CondoBillingDataChangedEvent;
use App\Domain\Condo\Event\CondoCreatedEvent;
use App\Domain\Condo\Event\CondoMaintenanceDataUpdatedEvent;
use App\Domain\Condo\Event\CondoPaymentDataChangedEvent;
use App\Domain\Condo\Exception\CondoAdminNotFoundException;
use App\Domain\Condo\Exception\CondoBuildingNotFoundException;
use App\Domain\DomainCriteria;
use App\Domain\DomainEventPublisher;
use App\Domain\EntryInstruction\EntryInstruction;
use App\Domain\GetEntityByIdInCollectionTrait;
use App\Domain\ImageRemoverEventTrait;
use App\Domain\Resident\Exception\ResidentCustomFieldNotFoundException;
use App\Domain\Resident\ResidentCustomField;
use App\Domain\Resident\ResidentCustomFieldValue;
use App\Domain\Transaction\Transaction;
use App\Domain\Unit\Exception\UnitCustomFieldNotFoundException;
use App\Domain\Unit\Exception\UnitNotFoundException;
use App\Domain\Unit\Unit;
use App\Domain\Unit\UnitCustomField;
use App\Domain\Unit\UnitCustomFieldValue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Condo implements ContainsRecordedMessages
{
    use
        GetEntityByIdInCollectionTrait,
        ImageRemoverEventTrait,
        PrivateMessageRecorderCapabilities;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=false)
     */
    private $createdAt;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $paymentAccountId;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isPaymentActive;

    /**
     * @var CondoGeneralData|null
     * @ORM\Embedded(class="CondoGeneralData")
     */
    protected $generalData;

    /**
     * @var CondoBillingData|null
     * @ORM\Embedded(class="CondoBillingData")
     */
    protected $billingData;

    /**
     * @var CondoWhitelabelData|null
     * @ORM\Embedded(class="CondoWhitelabelData")
     */
    protected $whitelabelData;

    /**
     * @var CondoMaintenanceData|null
     * @ORM\Embedded(class="CondoMaintenanceData")
     */
    protected $maintenanceData;

    /**
     * @var CondoPaymentData|null
     * @ORM\Embedded(class="CondoPaymentData")
     */
    protected $paymentData;

    /**
     * @var Image|null
     * @ORM\OneToOne(targetEntity="App\Domain\Common\Image")
     * @ORM\JoinColumn(nullable=true, unique=true, onDelete="SET NULL")
     */
    protected $image;

    /**
     * @var ArrayCollection CondoBuilding[]
     * @ORM\OneToMany(
     *     targetEntity="CondoBuilding",
     *     mappedBy="condo",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *   )
     */
    private $buildings;

    /**
     * @var Account|null
     * @ORM\ManyToOne(targetEntity="App\Domain\Account\Account", inversedBy="condos")
     */
    private $account;

    /**
     * @ORM\ManyToMany(targetEntity="CondoAdmin", mappedBy="condos", cascade={"persist"})
     * @ORM\JoinTable(name="condo_condoadmin")
     */
    private $admins;

    /**
     * @var ArrayCollection Unit[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Unit\Unit",
     *     mappedBy="condo",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *   )
     */
    private $units;

    /**
     * @var ArrayCollection UnitCustomField[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Unit\UnitCustomField",
     *     mappedBy="condo",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *   )
     */
    private $unitCustomFields;

    /**
     * @var ArrayCollection ResidentCustomField[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Resident\ResidentCustomField",
     *     mappedBy="condo",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *   )
     */
    private $residentCustomFields;

    /**
     * @var ArrayCollection Amenity[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Amenity\Amenity",
     *     mappedBy="condo",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *   )
     */
    private $amenities;

    /**
     * @var ArrayCollection Announcement[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Announcement\Announcement",
     *     mappedBy="condo",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *    )
     */
    private $announcements;

    /**
     * @var ArrayCollection EntryInstruction[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\EntryInstruction\EntryInstruction",
     *     mappedBy="condo",
     *     cascade={"persist"},
     *    )
     */
    private $entryInstructions;

    /**
     * @var ArrayCollection Transaction[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Transaction\Transaction",
     *     mappedBy="condo",
     *     cascade={"persist"},
     *   )
     */
    private $transactions;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new \DateTime();
        $this->buildings = new ArrayCollection();
        $this->admins = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->unitCustomFields = new ArrayCollection();
        $this->residentCustomFields = new ArrayCollection();
        $this->amenities = new ArrayCollection();
        $this->announcements = new ArrayCollection();
        $this->entryInstructions = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->paymentAccountId = '';
        $this->isPaymentActive = false;

        DomainEventPublisher::instance()->publish(
            new CondoCreatedEvent($this)
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function addBuilding(CondoBuilding $condoBuilding): self
    {
        if (!$this->buildings->contains($condoBuilding)) {
            $this->buildings->add(
                $condoBuilding->setCondo($this)
            );
        }

        return $this;
    }

    public function addUnitCustomField(UnitCustomField $unitCustomField): self
    {
        if (!$this->unitCustomFields->contains($unitCustomField)) {
            $this->unitCustomFields->add(
                $unitCustomField->setCondo($this)
            );

            // add field to already existed units
            foreach ($this->units as $unit) {
                $unit->addCustomFieldValue(new UnitCustomFieldValue($unitCustomField, $unit));
            }
        }

        return $this;
    }

    public function addResidentCustomField(ResidentCustomField $customField): self
    {
        if (!$this->residentCustomFields->contains($customField)) {
            $this->residentCustomFields->add(
                $customField->setCondo($this)
            );

            // add field to already existed residents
            foreach ($this->units as $unit) {
                foreach ($unit->getResidents() as $resident) {
                    $resident->addCustomFieldValue(new ResidentCustomFieldValue($customField, $resident));
                }
            }
        }

        return $this;
    }

    public function removeUnitCustomField(UnitCustomField $unitCustomField): self
    {
        $this->unitCustomFields->removeElement(
            $unitCustomField
        );

        return $this;
    }

    public function removeResidentCustomField(ResidentCustomField $customField): self
    {
        $this->residentCustomFields->removeElement(
            $customField
        );

        return $this;
    }

    public function addAdmin(CondoAdmin $admin): self
    {
        if (!$this->admins->contains($admin)) {
            $this->admins->add($admin->addCondo($this));
        }

        return $this;
    }

    public function addUnit(Unit $unit): self
    {
        if (!$this->units->contains($unit)) {
            $this->units->add($unit->setCondo($this));

            // add custom fields to unit
            foreach ($this->unitCustomFields as $unitCustomField) {
                $unit->addCustomFieldValue(new UnitCustomFieldValue($unitCustomField, $unit));
            }
        }

        return $this;
    }

    public function removeUnit(Unit $unit): self
    {
        //TODO ask to lukashevich
        $this->units->removeElement(
            $unit->setCondo($this)
        );

        return $this;
    }

    public function removeAdmin(CondoAdmin $admin): self
    {
        $this->admins->removeElement(
            $admin->removeCondo($this)
        );

        return $this;
    }

    /**
     * @return CondoBuilding[]|ArrayCollection
     */
    public function getBuildings(): iterable
    {
        return $this->buildings;
    }

    /**
     * @return CondoBuilding[]|ArrayCollection
     */
    public function getOrderedBuildings(): iterable
    {
        return $this
            ->buildings
            ->matching(
                Criteria::create()->orderBy(['fullNameLowerCase' => Criteria::ASC])
            );
    }

    /**
     * @return UnitCustomField[]|ArrayCollection
     */
    public function getUnitCustomFields(): iterable
    {
        return $this->unitCustomFields->matching(
            Criteria::create()->orderBy(['nameLowerCase' => 'ASC'])
        );
    }

    /**
     * @return ResidentCustomField[]|ArrayCollection
     */
    public function getResidentCustomFields(): iterable
    {
        return $this->residentCustomFields->matching(
            Criteria::create()->orderBy(['nameLowerCase' => 'ASC'])
        );
    }

    /**
     * @param DomainCriteria $criteria
     *
     * @return Collection|CondoAdmin[]
     */
    public function getAdminsByCriteria(DomainCriteria $criteria): Collection
    {
        return (new ArrayCollection($this->admins->toArray()))->matching($criteria->create());
    }

    public function getAdmin(string $id, bool $throwExceptionIfNotFound = true): ?CondoAdmin
    {
        $res = $this
            ->admins
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
     * @return int
     */
    public function getAdminsCount(): int
    {
        return $this->admins->count();
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getBuildingsCount(): int
    {
        return $this->buildings->count();
    }

    public function getUnitsCount(): int
    {
        return $this->units->count();
    }

    /**
     * @return Collection|Unit[]
     */
    public function getUnits(): Collection
    {
        return $this->units;
    }

    public function getName(): string
    {
        return $this->generalData->getName();
    }

    public function getManagingCompanyName(): string
    {
        return $this->billingData->getCompanyName();
    }

    public function getManagingCompanyAddress(): array
    {
        return $this->billingData->getAddress();
    }

    public function getDescription(): string
    {
        return $this->generalData->getDescription();
    }

    public function setGeneralData(CondoGeneralData $data): self
    {
        $this->generalData = $data;

        return $this;
    }

    public function setRawBuildings(array $buildings): self
    {
        $existedIds = [];
        foreach ($buildings as $building) {
            if (isset($building['id'])) {
                if ($res = $this->getBuilding($building['id'])) {
                    $res->updateInfo($building['name'], $building['number']);
                    $existedIds[] = $building['id'];
                }
            } else {
                $this->addBuilding(
                    $res = new CondoBuilding($building['name'], $building['number'])
                );
                $existedIds[] = $res->getId();
            }
        }

        // remove buildings which are not presented in request
        foreach ($this->buildings as $building) {
            if (!in_array($building->getId(), $existedIds)) {
                $building->remove();
            }
        }

        return $this;
    }

    public function getGeneralData(): ?array
    {
        return $this->generalData
            ? $this->generalData->toArray()
            : null;
    }

    public function setBillingData(CondoBillingData $data): self
    {
        $this->billingData = $data;

        DomainEventPublisher::instance()->publish(
            new CondoBillingDataChangedEvent($this->getId())
        );

        return $this;
    }

    public function getBillingData(): ?array
    {
        return $this->billingData
            ? $this->billingData->toArray()
            : null;
    }

    public function setPaymentData(CondoPaymentData $data): self
    {
        $this->paymentData = $data;

        DomainEventPublisher::instance()->publish(
            new CondoPaymentDataChangedEvent($this->getId())
        );

        return $this;
    }

    public function updatePaymentDataStatus(CondoPaymentData $data): self
    {
        $this->paymentData = $data;

        return $this;
    }

    public function setPaymentFees(
        float $vmcBankFee,
        float $vmcPlatformFee,
        float $amexBankFee,
        float $amexPlatformFee
    ): self {
        $this->paymentData->setFee($vmcBankFee, $vmcPlatformFee, $amexBankFee, $amexPlatformFee);

        DomainEventPublisher::instance()->publish(
            new CondoPaymentDataChangedEvent($this->getId())
        );

        return $this;
    }

    public function getPaymentType()
    {
        return $this->paymentData->getType();
    }

    public function getPaymentData(): ?array
    {
        return $this->paymentData
            ? $this->paymentData->toArray()
            : null;
    }

    public function getPaymentFees(): ?array
    {
        return $this->paymentData
            ? $this->paymentData->getFees()
            : null;
    }

    public function setWhitelabelData(CondoWhitelabelData $data): self
    {
        $this->whitelabelData = $data;

        return $this;
    }

    public function getWhitelabelData(): ?array
    {
        $res = $this->whitelabelData
            ? $this->whitelabelData->toArray()
            : null;

        if (!is_null($res)) {
            $res += [
                'logoUrl' => $this->image
                    ? $this->image->toArray()
                    : null,
            ];
        }

        return $res;
    }

    public function setMaintenanceData(CondoMaintenanceData $newMaintenanceData): self
    {
        $oldMaintenanceData = $this->maintenanceData;
        $this->maintenanceData = $newMaintenanceData;
        if ((!$oldMaintenanceData || !$oldMaintenanceData->getMaintenanceFeeSize())
            || $oldMaintenanceData->getMaintenanceFeeSize() !== $newMaintenanceData->getMaintenanceFeeSize()
        ) {
            DomainEventPublisher::instance()->publish(
                new CondoMaintenanceDataUpdatedEvent($this)
            );
        }

        return $this;
    }

    public function getMaintenanceData(): ?CondoMaintenanceData
    {
        return $this->maintenanceData && $this->maintenanceData->getMaintenanceFeeSize()
            ? $this->maintenanceData
            : null;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function getBuilding(string $id, bool $throwExceptionIfNotFound = true): ?CondoBuilding
    {
        $res = $this
            ->buildings
            ->matching(
                Criteria::create()->where(
                    Criteria::expr()->eq('id', $id)
                )
            );

        if ($throwExceptionIfNotFound && !$res->count()) {
            throw new CondoBuildingNotFoundException();
        }

        return $res->count()
            ? $res->first()
            : null;
    }

    public function getUnitCustomField(string $id, bool $throwExceptionIfNotFound = true): ?UnitCustomField
    {
        $res = $this
            ->unitCustomFields
            ->matching(
                Criteria::create()->where(
                    Criteria::expr()->eq('id', $id)
                )
            );

        if ($throwExceptionIfNotFound && !$res->count()) {
            throw new UnitCustomFieldNotFoundException();
        }

        return $res->count()
            ? $res->first()
            : null;
    }

    public function getResidentCustomField(string $id, bool $throwExceptionIfNotFound = true): ?ResidentCustomField
    {
        return $this->getEntityByIdInCollection(
            $id,
            $this->residentCustomFields,
            $throwExceptionIfNotFound,
            ResidentCustomFieldNotFoundException::class
        );
    }

    public function isUnitCustomFieldWithTheSameNameExists(string $name, string $fieldId): bool
    {
        return (bool) $this
            ->unitCustomFields
            ->matching(
                Criteria::create()->where(
                    Criteria::expr()->andX(
                        Criteria::expr()->eq('nameLowerCase', mb_strtolower($name)),
                        Criteria::expr()->neq('id', $fieldId)
                    )
                )
            )->count();
    }

    public function isResidentCustomFieldWithTheSameNameExists(string $name, string $fieldId): bool
    {
        return (bool) $this
            ->residentCustomFields
            ->matching(
                Criteria::create()->where(
                    Criteria::expr()->andX(
                        Criteria::expr()->eq('nameLowerCase', mb_strtolower($name)),
                        Criteria::expr()->neq('id', $fieldId)
                    )
                )
            )->count();
    }

    public function getUnit(string $id, bool $throwExceptionIfNotFound = true): ?Unit
    {
        $res = $this
            ->units
            ->matching(
                Criteria::create()->where(
                    Criteria::expr()->eq('id', $id)
                )
            );

        if ($throwExceptionIfNotFound && !$res->count()) {
            throw new UnitNotFoundException();
        }

        return $res->count()
            ? $res->first()
            : null;
    }

    public function addAmenity(Amenity $amenity): self
    {
        if (!$this->amenities->contains($amenity)) {
            $this->amenities->add(
                $amenity->setCondo($this)
            );
        }

        return $this;
    }

    public function addAnnouncement(Announcement $announcement): self
    {
        if (!$this->announcements->contains($announcement)) {
            $this->announcements->add(
                $announcement->setCondo($this)
            );
        }

        return $this;
    }

    public function addEntryInstruction(EntryInstruction $entryInstruction): self
    {
        if (!$this->entryInstructions->contains($entryInstruction)) {
            $this->entryInstructions->add(
                $entryInstruction->setCondo($this)
            );
        }

        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): self
    {
        $this->announcements->removeElement($announcement->setCondo(null));

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return clone $this->createdAt;
    }

    public function setPaymentAccountId(string $paymentAccountId): self
    {
        $this->paymentAccountId = $paymentAccountId;

        return $this;
    }

    public function getPaymentAccountId(): string
    {
        return $this->paymentAccountId;
    }

    public function getFeePayingUnits(): Collection
    {
        return $this
            ->units
            ->filter(function ($unit) {
                /*
                 * @var $unit Unit
                 */
                return (bool) $unit->getActiveResidentsCount();
            });
    }

    public function getOutstandingBalance(): int
    {
        return array_sum($this->units->map(function ($unit) {
            /*
             * @var $unit Unit
             * @TODO add status criteria on payment implementation
             */
            return array_sum($unit->getInvoices()->map(function ($invoice) {
                /*
                 * @var $invoice MaintenanceFeeInvoice
                 */
                return $invoice->getAmount();
            })->getValues());
        })->getValues());
    }

    public function getMaintenanceFeeDebt(): int
    {
        return array_sum($this->units->map(function ($unit) {
            /*
             * @var $unit Unit
             */
            return $unit->getMaintenanceFeeDebt();
        })->getValues());
    }

    public function getMaintenanceFeeTotalPaid(): int
    {
        return array_sum($this->units->map(function ($unit) {
            /*
             * @var $unit Unit
             */
            return $unit->getMaintenanceFeePaid();
        })->getValues());
    }

    public function getMaintenanceFeePendingThisMonth(): int
    {
        return array_sum($this->units->map(function ($unit) {
            /*
             * @var $unit Unit
             */
            return $unit->getMaintenanceFeePendingCurrentMonth();
        })->getValues());
    }

    public function getMaintenanceFeePaidThisMonth(): int
    {
        return array_sum($this->units->map(function ($unit) {
            /*
             * @var $unit Unit
             */
            return $unit->getMaintenanceFeePaidCurrentMonth();
        })->getValues());
    }

    public function isCondoPaymentDataFilled(): bool
    {
        return ($this->getPaymentData() && $this->getPaymentData()['type']) ? true : false;
    }

    public function isPaymentAvailable(): bool
    {
        //@TODO remove after pagamobil integration
        return true;

        return $this->isCondoPaymentDataFilled() && $this->isPaymentActive;
    }
}
