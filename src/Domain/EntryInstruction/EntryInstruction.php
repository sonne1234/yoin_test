<?php

namespace App\Domain\EntryInstruction;

use App\Domain\Amenity\AmenityTimeSlot;
use App\Domain\Common\Image;
use App\Domain\Condo\Condo;
use App\Domain\DomainEventPublisher;
use App\Domain\EntryInstruction\Event\EntryInstructionCancelledEvent;
use App\Domain\EntryInstruction\Event\EntryInstructionCreatedEvent;
use App\Domain\GetEntityByIdInCollectionTrait;
use App\Domain\Resident\Resident;
use App\Domain\User\UserIdentity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class EntryInstruction
{
    use
        GetEntityByIdInCollectionTrait;

    const STATUS_ACTUAL = 'actual';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';
    const STATUS_NOT_STARTED = 'not_started';

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isCanceled = false;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $cancelledAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=false)
     */
    private $periodStart;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="date", nullable=true)
     */
    private $periodEnd;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $visitorFirstName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $visitorLastName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $visitorCompany;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $visitorEmail;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $visitorAdditionalInfo;

    /**
     * @var Condo
     * @ORM\ManyToOne(targetEntity="App\Domain\Condo\Condo", inversedBy="entryInstructions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $condo;

    /**
     * @var Resident
     * @ORM\ManyToOne(targetEntity="App\Domain\Resident\Resident")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $resident;

    /**
     * @var UserIdentity
     * @ORM\ManyToOne(targetEntity="App\Domain\User\UserIdentity")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $createdBy;

    /**
     * @var Image|null
     * @ORM\ManyToOne(targetEntity="App\Domain\Common\Image")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $image;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isCancelledByAdmin = false;

    /**
     * @var EntryInstructionLog[]|ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\EntryInstruction\EntryInstructionLog",
     *     mappedBy="entryInstruction",
     *     cascade={"all"},
     *     orphanRemoval=true
     * )
     * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $logs;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isSingleEntry;

    /**
     * @var ?\DateTime
     */
    private $enterDate;

    /**
     * @var ?\DateTime
     */
    private $exitDate;

    public function __construct(
        UserIdentity $createdBy,
        Resident $resident,
        string $periodStart,
        string $periodEnd,
        string $visitorFirstName,
        string $visitorLastName,
        string $visitorCompany,
        string $visitorAdditionalInfo,
        string $visitorEmail,
        ?Image $image,
        bool $isSingleEntry
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->createdBy = $createdBy;
        $this->resident = $resident;
        $this->createdAt = new \DateTime();
        $this->visitorFirstName = $visitorFirstName;
        $this->visitorLastName = $visitorLastName;
        $this->visitorCompany = $visitorCompany;
        $this->visitorAdditionalInfo = $visitorAdditionalInfo;
        $this->visitorEmail = $visitorEmail;
        $this->periodStart = new \DateTime($periodStart);
        $this->periodEnd = $periodEnd
            ? new \DateTime($periodEnd)
            : null;
        $this->image = $image;
        $this->logs = new ArrayCollection();

        $this->isSingleEntry = $isSingleEntry;

        DomainEventPublisher::instance()->publish(
            new EntryInstructionCreatedEvent($this)
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPeriodStart(): \DateTime
    {
        return clone $this->periodStart;
    }

    public function getPeriodEnd(): ?\DateTime
    {
        return $this->periodEnd
            ? clone $this->periodEnd
            : null;
    }

    public function getVisitorFirstName(): string
    {
        return $this->visitorFirstName;
    }

    public function getVisitorLastName(): string
    {
        return $this->visitorLastName;
    }

    public function getVisitorCompany(): string
    {
        return $this->visitorCompany;
    }

    public function getVisitorEmail(): string
    {
        return $this->visitorEmail;
    }

    public function getVisitorAdditionalInfo(): string
    {
        return $this->visitorAdditionalInfo;
    }

    public function getResident(): Resident
    {
        return $this->resident;
    }

    public function getCondo(): Condo
    {
        return $this->condo;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function getCreatedAt(): \DateTime
    {
        return clone $this->createdAt;
    }

    public function setCondo(Condo $condo): self
    {
        $this->condo = $condo;

        return $this;
    }

    public function cancel($isAdmin = false): self
    {
        $this->isCanceled = true;
        $this->cancelledAt = new \DateTime();
        if ($isAdmin) {
            $this->isCancelledByAdmin = true;
        }

        DomainEventPublisher::instance()->publish(
            new EntryInstructionCancelledEvent($this)
        );

        return $this;
    }

    public function getStatus(): string
    {
        if ($this->isCanceled) {
            $status = self::STATUS_CANCELLED;
        } else {
            $periodEnd = $this->periodEnd
                ? \DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    $this->periodEnd->format('Y-m-d 00:00:00'),
                    new \DateTimeZone(AmenityTimeSlot::TIMEZONE)
                )
                : null;
            $periodStart = \DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $this->periodStart->format('Y-m-d 00:00:00'),
                new \DateTimeZone(AmenityTimeSlot::TIMEZONE)
            );

            $currentDate = (new \DateTime(null, new \DateTimeZone(AmenityTimeSlot::TIMEZONE)))->setTime(0, 0);
            if (($periodEnd && $periodEnd < $currentDate)
                || (!$periodEnd && $periodStart < $currentDate)
            ) {
                $status = self::STATUS_EXPIRED;
            } elseif ($periodStart > $currentDate) {
                $status = self::STATUS_NOT_STARTED;
            } else {
                $status = self::STATUS_ACTUAL;
            }
        }

        return $status;
    }

    public function getIsCancelledByAdmin(): bool
    {
        return $this->isCancelledByAdmin;
    }

    public function getCreatedBy(): UserIdentity
    {
        return $this->createdBy;
    }

    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(EntryInstructionLog $log): self
    {
        $this->logs->add($log);

        return $this;
    }

    public function getLatestLogEntry(): ?EntryInstructionLog
    {
        return $this->getLogs()->isEmpty() ? null : $this->getLogs()->last();
    }

    /**
     * @return mixed
     */
    public function getEnterDate(): ?\DateTime
    {
        return $this->enterDate ? clone $this->enterDate : null;
    }

    public function setEnterDate(?string $enterDate): self
    {
        $this->enterDate = $enterDate ? new \DateTime($enterDate) : null;

        return $this;
    }

    public function getExitDate(): ?\DateTime
    {
        return $this->exitDate ? clone $this->exitDate : null;
    }

    public function setExitDate(?string $exitDate): self
    {
        $this->exitDate = $exitDate ? new \DateTime($exitDate) : null;

        return $this;
    }

    public function isForToday(): bool
    {
        $currentDate = (new \DateTime(null, new \DateTimeZone(AmenityTimeSlot::TIMEZONE)))->setTime(0, 0);

        $periodStart = \DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $this->periodStart->format('Y-m-d 00:00:00'),
            new \DateTimeZone(AmenityTimeSlot::TIMEZONE)
        );

        $periodEnd = $this->periodEnd
            ? \DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $this->periodEnd->format('Y-m-d 00:00:00'),
                new \DateTimeZone(AmenityTimeSlot::TIMEZONE)
            )
            : null;

        return
            (!$periodEnd && $currentDate == $periodStart) ||
            ($periodEnd && $periodStart <= $currentDate && $periodEnd >= $currentDate);
    }

    public function isSingleEntry(): ?bool
    {
        return $this->isSingleEntry;
    }
}
