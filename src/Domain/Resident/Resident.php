<?php

namespace App\Domain\Resident;

use App\Domain\Common\Image;
use App\Domain\Condo\Transformer\CondoBuildingTransformer;
use App\Domain\Device\Device;
use App\Domain\Device\DeviceAttachable;
use App\Domain\DomainEventPublisher;
use App\Domain\DomainTransformer;
use App\Domain\ImageRemoverEventTrait;
use App\Domain\Resident\Event\ResidentCreatedEvent;
use App\Domain\Resident\Event\ResidentNotificationDisabledEvent;
use App\Domain\Resident\Event\ResidentNotificationEnabledEvent;
use App\Domain\Resident\Exception\PrimeResidentCanNotBeDeactivatedException;
use App\Domain\Resident\Exception\ResidentCustomFieldIsRequiredException;
use App\Domain\Resident\Transformer\ResidentTransformer;
use App\Domain\ServiceRequest\ServiceRequest;
use App\Domain\Transaction\Transaction;
use App\Domain\Unit\Unit;
use App\Domain\User\UserIdentity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Resident extends UserIdentity implements ContainsRecordedMessages, DeviceAttachable
{
    use ImageRemoverEventTrait;
    use PrivateMessageRecorderCapabilities;

    public const RESIDENT_TYPE_PRIME = 'prime';
    public const RESIDENT_TYPE_SUB = 'sub';
    public const RESIDENT_TYPES = [
        self::RESIDENT_TYPE_PRIME,
        self::RESIDENT_TYPE_SUB,
    ];
    public const GENDERS = [
        'male',
        'female',
        'other',
    ];

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=false)
     */
    private $birthday;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $gender;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $homePhone;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $cellPhone;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $relationship;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $paymentProviderId;

    /**
     * @var Unit
     * @ORM\ManyToOne(targetEntity="App\Domain\Unit\Unit", inversedBy="residents")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $unit;

    //    /**
    //     * @var EntryInstruction[]|ArrayCollection
    //     * @ORM\OneToMany(
    //     *     targetEntity="App\Domain\EntryInstruction\EntryInstruction",
    //     *     mappedBy="resident",
    //     *     cascade={"persist", "remove"}
    //     *   )
    //     */
    //    private $entryInstructions;

    /**
     * @var ArrayCollection ResidentCustomFieldValue[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Resident\ResidentCustomFieldValue",
     *     mappedBy="resident",
     *     cascade={"persist"}
     *   )
     */
    private $customFieldValues;

    /**
     * @var ArrayCollection ServiceRequest[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\ServiceRequest\ServiceRequest",
     *     mappedBy="resident",
     *     cascade={"persist"}
     *   )
     */
    private $serviceRequests;

    /**
     * @var ArrayCollection Device[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Device\Device",
     *     mappedBy="resident",
     *     cascade={"persist"}
     *   )
     */
    private $devices;

    /**
     * @var ArrayCollection Transaction[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Transaction\Transaction",
     *     mappedBy="resident",
     *     cascade={"persist"},
     *   )
     */
    private $transactions;

    public function __construct(
        string $email,
        string $password,
        string $firstName,
        string $lastName,
        ?Image $image,
        string $phone = ''
    ) {
        $this->customFieldValues = new ArrayCollection();
        $this->devices = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->paymentProviderId = '';

        parent::__construct($email, $password, UserIdentity::ROLE_RESIDENT, $image, $firstName, $lastName, $phone);

        DomainEventPublisher::instance()->publish(
            new ResidentCreatedEvent(
                $this,
                $this->initialPasswordLink
            )
        );
    }

    public function deactivate(): UserIdentity
    {
        // primary user can be deactivated only when another active/pending primary user exists
        if (self::RESIDENT_TYPE_PRIME === $this->type && $this->unit) {
            if (!$this->unit->isAtLeastOneActivePrimeResidentExists($this)) {
                throw new PrimeResidentCanNotBeDeactivatedException();
            }
        }

        return parent::deactivate();
    }

    public function getUserTransformer(): DomainTransformer
    {
        return new ResidentTransformer(true);
    }

    public function updateResidentInfo(
        string $email,
        string $firstName,
        string $lastName,
        ?Image $image,
        string $birthday,
        string $gender,
        string $homePhone,
        string $cellPhone,
        string $type,
        string $relationship,
        Unit $unit,
        array $requestCustomFields
    ): self {
        $this->birthday = new \DateTime($birthday);
        $this->gender = $gender;
        $this->homePhone = $homePhone;
        $this->cellPhone = $cellPhone;
        $this->type = $type;
        $this->relationship = $relationship;

        parent::updateInfo($email, $firstName, $lastName, $image, '');

        if ($this->unit) {
            $this->unit->removeResident($this);
        }
        $unit->addResident($this);

        $requestCustomFields = array_combine(array_column($requestCustomFields, 'id'), $requestCustomFields);
        foreach ($this->customFieldValues as $unitCustomFieldValue) {
            /** @var ResidentCustomFieldValue $unitCustomFieldValue */
            $value = !isset($requestCustomFields[$unitCustomFieldValue->getCustomFieldId()])
                ? ''
                : trim((string) $requestCustomFields[$unitCustomFieldValue->getCustomFieldId()]['value']);
            if ($unitCustomFieldValue->getCustomFieldIsRequired() && '' === $value) {
                throw new ResidentCustomFieldIsRequiredException($unitCustomFieldValue);
            }
            $unitCustomFieldValue->setValue($value);
        }

        return $this;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function toArray(bool $isShowCustomFieldsOnlyObservableForResidents, bool $isSkipCustomFields): array
    {
        if (!$isSkipCustomFields) {
            $customFields = array_map(
                function ($field) {
                    /* @var ResidentCustomFieldValue $field */
                    return $field->toArray();
                },
                iterator_to_array(
                    (new ArrayCollection(iterator_to_array($this->customFieldValues)))
                        ->matching(Criteria::create()->orderBy(['customFieldNameLowerCase' => 'ASC']))
                )
            );

            if ($isShowCustomFieldsOnlyObservableForResidents) {
                $customFields = array_filter(
                    $customFields,
                    function ($val) {
                        return $val['isObservable'] ?? null;
                    }
                );
            }
        } else {
            $customFields = [];
        }

        return [
            'birthday' => $this->birthday->format(\DateTime::ATOM),
            'gender' => $this->gender,
            'homePhone' => $this->homePhone,
            'cellPhone' => $this->cellPhone,
            'type' => $this->type,
            'relationship' => $this->relationship,
            'accountId' => $this->unit && $this->unit->getCondo() && $this->unit->getCondo()->getAccount()
                ? $this->unit->getCondo()->getAccount()->getId()
                : null,
            'condoId' => $this->unit && $this->unit->getCondo()
                ? $this->unit->getCondo()->getId()
                : null,
            'unitId' => $this->unit
                ? $this->unit->getId()
                : null,
            'unitNumber' => $this->unit
                ? $this->unit->getNumber()
                : null,
            'condoBuilding' => $this->unit && ($building = $this->unit->getCondoBuilding())
                ? (new CondoBuildingTransformer())->transform($building)
                : null,
            'customFields' => array_values($customFields),
            'paymentProviderId' => $this->paymentProviderId,
        ];
    }

    public function addCustomFieldValue(ResidentCustomFieldValue $value): self
    {
        if (!$this->customFieldValues->contains($value)) {
            foreach ($this->customFieldValues as $customFieldValue) {
                if ($customFieldValue->getCustomFieldId() === $value->getCustomFieldId()) {
                    return $this;
                }
            }
            $this->customFieldValues->add($value);
        }

        return $this;
    }

    public function isPrime(): bool
    {
        return self::RESIDENT_TYPE_PRIME === $this->type;
    }

    public function setPaymentProviderId(string $paymentProviderId): self
    {
        $this->paymentProviderId = $paymentProviderId;

        return $this;
    }

    public function disableNotifications(): UserIdentity
    {
        $return = parent::disableNotifications();
        $this->record(new ResidentNotificationDisabledEvent($this));

        return $return;
    }

    public function enableNotifications(): UserIdentity
    {
        $return = parent::enableNotifications();
        $this->record(new ResidentNotificationEnabledEvent($this));

        return $return;
    }

    public function getDevices()
    {
        return $this->devices;
    }

    public function hasDevice(Device $device): bool
    {
        return $this->devices->contains($device);
    }

    public function attachDevice(Device $device)
    {
        if (!$this->hasDevice($device)) {
            $this->devices->add($device);
            $device->setResident($this);
            if ($this->getIsNotificationsEnabled()) {
                $this->record(new ResidentNotificationEnabledEvent($this));
            }
        }
    }

    public function detachDevice(Device $device)
    {
        if ($this->hasDevice($device)) {
            $this->devices->removeElement($device);
            $device->setResident(null);
            $this->record(new ResidentNotificationDisabledEvent($this, $device));
        }
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
        }

        return $this;
    }
}
