<?php

namespace App\Domain\Unit;

use App\Domain\Common\Image;
use App\Domain\Condo\Condo;
use App\Domain\Condo\CondoBuilding;
use App\Domain\Condo\Transformer\CondoBuildingTransformer;
use App\Domain\GetEntityByIdInCollectionTrait;
use App\Domain\Invoice\MaintenanceFeeInvoice;
use App\Domain\Resident\Event\ResidentCheckedIn;
use App\Domain\Resident\Event\ResidentCheckedOut;
use App\Domain\Resident\Exception\ResidentNotFoundException;
use App\Domain\Resident\Resident;
use App\Domain\Resident\ResidentCustomFieldValue;
use App\Domain\Resident\Transformer\ResidentShortInfoTransformer;
use App\Domain\Unit\Exception\PetNotFoundException;
use App\Domain\Unit\Exception\PetsLimitExceedException;
use App\Domain\Unit\Exception\UnitCustomFieldIsRequiredException;
use App\Domain\Unit\Transformer\PetTransformer;
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
 * @ORM\Table(
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="number_building", columns={"numberlowercase", "condobuilding_id"})
 *  }
 * )
 */
class Unit implements ContainsRecordedMessages
{
    use GetEntityByIdInCollectionTrait;
    use PrivateMessageRecorderCapabilities;

    const MAX_PETS_LIMIT = 20;

    const TABLE_NAME = 'unit';

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $number;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $numberLowerCase;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $phoneNumber;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $parkingSpots;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $bicycleSpots;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $description;

    /**
     * @var Condo|null
     * @ORM\ManyToOne(targetEntity="App\Domain\Condo\Condo", inversedBy="units")
     * @ORM\JoinColumn(nullable=false)
     */
    private $condo;

    /**
     * @var CondoBuilding|null
     * @ORM\ManyToOne(targetEntity="App\Domain\Condo\CondoBuilding", inversedBy="units")
     * @ORM\JoinColumn(nullable=false)
     */
    private $condoBuilding;

    /**
     * @var ArrayCollection UnitCustomFieldValue[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Unit\UnitCustomFieldValue",
     *     mappedBy="unit",
     *     cascade={"persist"}
     *   )
     */
    private $customFieldValues;

    /**
     * @var ArrayCollection Resident[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Resident\Resident",
     *     mappedBy="unit",
     *     cascade={"persist", "remove"}
     *   )
     */
    private $residents;

    /**
     * @var ArrayCollection Pet[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Unit\Pet",
     *     mappedBy="unit",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *   )
     */
    private $pets;

    /**
     * @var ArrayCollection MaintenanceFeeInvoice[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Invoice\MaintenanceFeeInvoice",
     *     mappedBy="unit",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *   )
     */
    private $invoices;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->customFieldValues = new ArrayCollection();
        $this->residents = new ArrayCollection();
        $this->pets = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getNumberLowerCase(): string
    {
        return $this->numberLowerCase;
    }

    public function setCondo(?Condo $condo): self
    {
        $this->condo = $condo;

        return $this;
    }

    public function getCondo(): ?Condo
    {
        return $this->condo;
    }

    public function setCondoBuilding(?CondoBuilding $condoBuilding): self
    {
        $this->condoBuilding = $condoBuilding;

        return $this;
    }

    public function getCondoBuilding(): ?CondoBuilding
    {
        return $this->condoBuilding;
    }

    public function updateInfo(
        string $number,
        string $phoneNumber,
        string $parkingSpots,
        string $bicycleSpots,
        string $description,
        CondoBuilding $condoBuilding,
        array $requestCustomFields
    ): self {
        $this->number = trim($number);
        $this->numberLowerCase = mb_strtolower($this->number);
        $this->phoneNumber = $phoneNumber;
        $this->parkingSpots = $parkingSpots;
        $this->bicycleSpots = $bicycleSpots;
        $this->description = $description;

        //TODO @lukashevich  - for what?
        if ($this->condoBuilding) {
            $this->condoBuilding->removeUnit($this);
        }
        $condoBuilding->addUnit($this);
        $this->setCondo($condoBuilding->getCondo());

        $requestCustomFields = array_combine(array_column($requestCustomFields, 'id'), $requestCustomFields);
        foreach ($this->customFieldValues as $unitCustomFieldValue) {
            /** @var UnitCustomFieldValue $unitCustomFieldValue */
            $value = !isset($requestCustomFields[$unitCustomFieldValue->getCustomFieldId()])
                ? ''
                : trim((string) $requestCustomFields[$unitCustomFieldValue->getCustomFieldId()]['value']);
            if ($unitCustomFieldValue->getCustomFieldIsRequired() && '' === $value) {
                throw new UnitCustomFieldIsRequiredException($unitCustomFieldValue);
            }
            $unitCustomFieldValue->setValue($value);
        }

        return $this;
    }

    public function toArray(
        $showCustomFieldsOnlyObservableForResidents = false,
        bool $isShowFirstPrimeUserNumber = true
    ): array {
        $customFields = array_map(
            function ($field) {
                /* @var UnitCustomFieldValue $field */
                return $field->toArray();
            },
            iterator_to_array(
                (new ArrayCollection(iterator_to_array($this->customFieldValues)))
                    ->matching(Criteria::create()->orderBy(['customFieldNameLowerCase' => 'ASC']))
            )
        );

        if ($showCustomFieldsOnlyObservableForResidents) {
            $customFields = array_filter(
                $customFields,
                function ($val) {
                    return $val['isObservable'] ?? null;
                }
            );
        }

        return [
            'id' => $this->id,
            'condoId' => $this->condo
                ? $this->condo->getId()
                : null,
            'condoBuilding' => $this->condoBuilding
                ? (new CondoBuildingTransformer())->transform($this->condoBuilding)
                : null,
            'number' => $this->number,
            'phoneNumber' => $this->phoneNumber,
            'parkingSpots' => $this->parkingSpots,
            'bicycleSpots' => $this->bicycleSpots,
            'description' => $this->description,
            'pets' => (new PetTransformer())->transform($this->getPets()),
            'customFields' => array_values($customFields),
            'firstPrimeUser' => ($user = $this->getFirstPrimeUser())
                ? (new ResidentShortInfoTransformer())
                    ->setIsShowResidentNumber($isShowFirstPrimeUserNumber)
                    ->transform($user)
                : null,
        ];
    }

    public function addCustomFieldValue(UnitCustomFieldValue $value)
    {
        if (!$this->customFieldValues->contains($value)) {
            $this->customFieldValues->add($value);
        }
    }

    public function addResident(Resident $resident)
    {
        if (!$this->residents->contains($resident)) {
            $this->residents->add($resident->setUnit($this));
            $this->record(new ResidentCheckedIn($resident, $this->getCondoBuilding()));

            // add custom fields to resident
            foreach ($this->condo->getResidentCustomFields() as $customField) {
                $resident->addCustomFieldValue(new ResidentCustomFieldValue($customField, $resident));
            }
        }
    }

    public function removeResident(Resident $resident)
    {
        $this->residents->removeElement($resident->setUnit(null));
        $this->record(new ResidentCheckedOut($resident, $this->getCondoBuilding()));
    }

    public function isAtLeastOnePrimeResidentExists(Resident $currentResident = null): bool
    {
        return (bool) $this->residents->matching(
            Criteria::create()
                ->where(
                    Criteria::expr()->andX(
                        Criteria::expr()->eq('type', Resident::RESIDENT_TYPE_PRIME),
                        Criteria::expr()->neq('id', $currentResident ? $currentResident->getId() : '')
                    )
                )
        )->count();
    }

    public function isAtLeastOneActivePrimeResidentExists(Resident $currentResident): bool
    {
        return (bool) $this->residents->matching(
            Criteria::create()
                ->where(
                    Criteria::expr()->andX(
                        Criteria::expr()->eq('type', Resident::RESIDENT_TYPE_PRIME),
                        Criteria::expr()->neq('id', $currentResident->getId()),
                        Criteria::expr()->orX(
                            Criteria::expr()->eq('isActive', true),
                            Criteria::expr()->andX(
                                Criteria::expr()->neq('email', null),
                                Criteria::expr()->isNull('initializedAt')
                            )
                        )
                    )
                )
        )->count();
    }

    public function isAtLeastOneSubResidentExists(Resident $currentResident): bool
    {
        return (bool) $this->residents->matching(
            Criteria::create()
                ->where(
                    Criteria::expr()->andX(
                        Criteria::expr()->eq('type', Resident::RESIDENT_TYPE_SUB),
                        Criteria::expr()->neq('id', $currentResident->getId())
                    )
                )
        )->count();
    }

    public function getResident(string $id, bool $throwExceptionIfNotFound = true): ?Resident
    {
        return $this->getEntityByIdInCollection(
            $id,
            $this->residents,
            $throwExceptionIfNotFound,
            ResidentNotFoundException::class
        );
    }

    /**
     * @return ArrayCollection Resident[]
     */
    public function getResidents(): Collection
    {
        return $this->residents;
    }

    /**
     * @return int
     */
    public function getResidentsCount(): int
    {
        return $this->residents->count();
    }

    /**
     * @return int
     */
    public function getActiveResidentsCount(): int
    {
        return $this
            ->residents
            ->matching(Criteria::create()->where(Criteria::expr()->eq('isActive', true)))
            ->count();
    }

    /**
     * @return int
     */
    public function getNotDeactivatedResidentsCount(): int
    {
        return $this
            ->residents
            ->matching(
                Criteria::create()->where(
                    Criteria::expr()->orX(
                        Criteria::expr()->andX(
                            Criteria::expr()->eq('isActive', true),
                            Criteria::expr()->neq('initializedAt', null)
                        ),
                        Criteria::expr()->isNull('initializedAt')
                    )
                )
            )
            ->count();
    }

    /**
     * @return Resident[]
     */
    public function getOrderedResidents(): array
    {
        //Prime users are above the other subusers sorted in alphabetical order by Last name;
        //other subusers displayed in order they're added to the list.
        return array_merge(
            $this->residents->matching(
                Criteria::create()
                    ->where(Criteria::expr()->eq('type', Resident::RESIDENT_TYPE_PRIME))
                    ->orderBy(['lastName' => Criteria::ASC])
            )->toArray(),
            $this->residents->matching(
                Criteria::create()
                    ->where(Criteria::expr()->eq('type', Resident::RESIDENT_TYPE_SUB))
                    ->orderBy(['createdAt' => Criteria::ASC])
            )->toArray()
        );
    }

    public function getFirstPrimeUser(): ?Resident
    {
        if (count($res = $this->residents->matching(
            Criteria::create()
                ->where(Criteria::expr()->andX(
                    Criteria::expr()->eq('type', Resident::RESIDENT_TYPE_PRIME)
                ))
                ->orderBy([
                    'initializedAt' => Criteria::ASC,
                    'isActive' => Criteria::DESC,
                    'createdAt' => Criteria::ASC,
                ])
                ->setMaxResults(1)
        ))) {
            return $res->first();
        }

        return null;
    }

    public function getPet(string $id, bool $throwExceptionIfNotFound = true): ?Pet
    {
        return $this->getEntityByIdInCollection(
            $id,
            $this->pets,
            $throwExceptionIfNotFound,
            PetNotFoundException::class
        );
    }

    public function getPets(): iterable
    {
        return $this->pets->matching(
            Criteria::create()->orderBy([
                'type' => 'ASC',
                'name' => 'ASC',
            ])
        );
    }

    public function addPet(string $type, string $typeOther, string $name, string $description, ?Image $image): Pet
    {
        $pet = (new Pet($this))->update(
            $type,
            $typeOther,
            $name,
            $description,
            $image
        );

        $this->pets->add($pet);

        if ($this->pets->count() > self::MAX_PETS_LIMIT) {
            throw new PetsLimitExceedException();
        }

        return $pet;
    }

    public function clearPets(array $usedImagesIds): self
    {
        foreach ($this->pets as $pet) {
            if (($image = $pet->getImage()) && in_array($image->getId(), $usedImagesIds)) {
                $pet->unsetImage();
            }
            $this->pets->removeElement($pet->unsetUnit());
        }

        return $this;
    }

    public function addInvoice(MaintenanceFeeInvoice $invoice)
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice->setUnit($this));
        }
    }

    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function getMaintenanceFeeDebt(): int
    {
        if (!($condo = $this->getCondo()) || !($maintenanceData = $this->getCondo()->getMaintenanceData())
        ) {
            return 0;
        }

        return array_sum($this->invoices->matching(
            Criteria::create()
                ->where(Criteria::expr()->andX(
                    Criteria::expr()->eq('isPaid', false),
                    Criteria::expr()->lt('payPeriod', $maintenanceData->getPayPeriod()->modify('-1 month'))
                )))->map(function ($invoice) {
                    /*
                     * @var $invoice MaintenanceFeeInvoice
                     */
                    return $invoice->getAmount();
                })->getValues());
    }

    public function isMaintenanceFeePaidForCurrentPeriod(): ?bool
    {
        if (!($condo = $this->getCondo()) || !($maintenanceData = $this->getCondo()->getMaintenanceData())) {
            return null;
        }

        return (bool) (!$this->invoices->matching(
            Criteria::create()
                ->where(Criteria::expr()->andX(
                    Criteria::expr()->eq('isPaid', true),
                    Criteria::expr()->eq('payPeriod', $maintenanceData->getPayPeriod()->modify('-1 month'))
                ))
        )->isEmpty());
    }

    /**
     * @TODO refactor maintenance fee getters
     */
    public function getMaintenanceFeePaid(): ?int
    {
        if (!($condo = $this->getCondo()) || !($maintenanceData = $this->getCondo()->getMaintenanceData())
        ) {
            return null;
        }

        return array_sum($this->invoices->matching(
            Criteria::create()
                ->where(
                    Criteria::expr()->eq('isPaid', true)
                ))->map(function ($invoice) {
                    /*
                     * @var $invoice MaintenanceFeeInvoice
                     */
                    return $invoice->getAmount();
                })->getValues());
    }

    public function getMaintenanceFeePendingCurrentMonth(): ?int
    {
        if (!($condo = $this->getCondo()) || !($maintenanceData = $this->getCondo()->getMaintenanceData())
        ) {
            return null;
        }

        return array_sum($this->invoices->matching(
            Criteria::create()
                ->where(Criteria::expr()->andX(
                    Criteria::expr()->eq('isPaid', false),
                    Criteria::expr()->eq('payPeriod', $maintenanceData->getPayPeriod()->modify('-1 month'))
                )))->map(function ($invoice) {
                    /*
                     * @var $invoice MaintenanceFeeInvoice
                     */
                    return $invoice->getAmount();
                })->getValues());
    }

    public function getMaintenanceFeePaidCurrentMonth(): ?int
    {
        if (!($condo = $this->getCondo()) || !($maintenanceData = $this->getCondo()->getMaintenanceData())
        ) {
            return null;
        }

        return array_sum($this->invoices->matching(
            Criteria::create()
                ->where(Criteria::expr()->andX(
                    Criteria::expr()->eq('isPaid', true),
                    Criteria::expr()->eq('payPeriod', $maintenanceData->getPayPeriod()->modify('-1 month'))
                )))->map(function ($invoice) {
                    /*
                     * @var $invoice MaintenanceFeeInvoice
                     */
                    return $invoice->getAmount();
                })->getValues());
    }
}
