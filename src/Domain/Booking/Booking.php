<?php

namespace App\Domain\Booking;

use App\Domain\Amenity\Amenity;
use App\Domain\Amenity\AmenityTimeSlot;
use App\Domain\Booking\Event\BookingApprovedEvent;
use App\Domain\Booking\Event\BookingCancelledEvent;
use App\Domain\Booking\Event\BookingDeclinedEvent;
use App\Domain\Booking\Event\BookingStatusChangedToWaitingApprovalEvent;
use App\Domain\Booking\Exception\BookingCanNotBeApprovedException;
use App\Domain\Booking\Exception\BookingCanNotBeCancelledException;
use App\Domain\Booking\Exception\BookingCanNotBeDeclinedException;
use App\Domain\Booking\Exception\BookingCanNotBeMarkedAsPaidException;
use App\Domain\Booking\Exception\BookingCanNotBeMarkedAsRefundedException;
use App\Domain\DomainEventPublisher;
use App\Domain\Resident\Resident;
use App\Domain\Transaction\Transaction;
use App\Domain\User\UserIdentity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class Booking
{
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const STATUS_APPROVAL_GRANTED = 'approval_granted';
    const STATUS_APPROVAL_DECLINED = 'approval_declined';
    const STATUS_WAITING_APPROVAL = 'waiting_approval';

    const STATUS_WAITING_PAYMENT = 'waiting_payment';

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
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $cancelledAt;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $status;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $placesCount;

    /**
     * @var Resident|null
     * @ORM\ManyToOne(targetEntity="App\Domain\Resident\Resident")
     * @ORM\JoinColumn(nullable=true)
     */
    private $resident;

    /**
     * @var UserIdentity
     * @ORM\ManyToOne(targetEntity="App\Domain\User\UserIdentity")
     * @ORM\JoinColumn(nullable=true)
     */
    private $createdBy;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="App\Domain\Amenity\AmenityTimeSlot",
     *      inversedBy="bookings",
     *      cascade={"persist"}
     *     )
     */
    private $timeSlots;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\Domain\Amenity\Amenity"
     *     )
     */
    private $amenity;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $paymentAmount;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $paymentPerSlot;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $timeSlotsCount;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $cancellationReason = '';

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isBookingFree;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isNotRefundedYet = false;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $refundedByPaymentProviderAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $refundedByAdminAt;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isRefundable = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isCancelledByAdmin = false;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $paidByCardAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $paidByCashAt;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isTemporaryPaid = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isPaymentTimeExceeded = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isCreatedByResident;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=false)
     */
    private $firstBookingDate;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=false)
     */
    private $firstBookingDateTime;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=false)
     */
    private $endBookingDateTime;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $administrationBookingStartDate;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $administrationBookingEndDate;

    /**
     * @var ArrayCollection Transaction[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Transaction\Transaction",
     *     mappedBy="booking",
     *     cascade={"persist"},
     *   )
     */
    private $transactions;

    /**
     * @param AmenityTimeSlot[]|ArrayCollection $timeSlots
     * @param int                               $placesCount
     * @param UserIdentity|null                 $createdBy
     * @param Resident|null                     $resident
     * @param float                             $paymentAmount
     * @param \DateTime|null                    $administrationBookingStartDate
     * @param \DateTime|null                    $administrationBookingEndDate
     */
    public function __construct(
        iterable $timeSlots,
        int $placesCount,
        ?UserIdentity $createdBy,
        ?Resident $resident,
        float $paymentAmount,
        \DateTime $administrationBookingStartDate = null,
        \DateTime $administrationBookingEndDate = null
    ) {
        if (!$timeSlotsCount = count($timeSlots)) {
            throw new \LogicException('Count of timeSlots can not be zero.');
        }

        if ((!$resident && (!$administrationBookingStartDate || !$administrationBookingEndDate)) ||
            ($resident && ($administrationBookingStartDate || $administrationBookingEndDate))
        ) {
            throw new \LogicException('You should (should not) provide administration booking dates.');
        }

        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new \DateTime();
        $this->resident = $resident;
        $this->createdBy = $createdBy;
        $this->placesCount = $placesCount;
        $this->paymentAmount = bcdiv($paymentAmount, 1, 2) * 100;
        $this->paymentPerSlot = (int) floor($this->paymentAmount / $timeSlotsCount);
        $this->isBookingFree = !$paymentAmount;
        $this->isCreatedByResident = $createdBy && $createdBy instanceof Resident;

        $firstTimeSlot = is_array($timeSlots)
            ? $timeSlots[0]
            : $timeSlots->first();
        $lastTimeSlot = is_array($timeSlots)
            ? $timeSlots[count($timeSlots) - 1]
            : $timeSlots->last();
        $this->firstBookingDate = $firstTimeSlot->getDate(); // date

        $this->amenity = $firstTimeSlot->getAmenity();

        // dates along with time
        if (AmenityTimeSlot::TYPE_TIME_ON_DEMAND === $firstTimeSlot->getType()) {
            $this->firstBookingDateTime = $firstTimeSlot->getTimeFrom();
            $this->endBookingDateTime = $lastTimeSlot->getTimeTill();
        } elseif (($amenity = $firstTimeSlot->getAmenity()) &&
            ($firstSlotWorkingTime = $amenity->getWorkingHoursForDayOfWeek($firstTimeSlot->getDayOfWeek())) &&
            ($lastSlotWorkingTime = $amenity->getWorkingHoursForDayOfWeek($lastTimeSlot->getDayOfWeek()))
        ) {
            // find start and end working hours of amenity
            $this->firstBookingDateTime = $firstTimeSlot
                ->getDate()
                ->setTimezone(new \DateTimeZone(AmenityTimeSlot::TIMEZONE))
                ->setTime($firstSlotWorkingTime->start[0], $firstSlotWorkingTime->start[1]);
            $this->endBookingDateTime = $lastTimeSlot
                ->getDate()
                ->setTimezone(new \DateTimeZone(AmenityTimeSlot::TIMEZONE))
                ->setTime($lastSlotWorkingTime->end[0], $lastSlotWorkingTime->end[1], 59);
        } else {
            throw new \LogicException(
                'Parameter firstBookingDateTime or endBookingDateTime can not be determined'
            );
        }

        $this->administrationBookingStartDate = $administrationBookingStartDate;
        $this->administrationBookingEndDate = $administrationBookingEndDate;

        $this->transactions = new ArrayCollection();

        $this->timeSlotsCount = $timeSlotsCount;
        $this->timeSlots = new ArrayCollection();
        foreach ($timeSlots as $slot) {
            $this->timeSlots->add($slot->addBooking($this));
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function markAsCompleted(): self
    {
        $this->status = self::STATUS_COMPLETED;

        return $this;
    }

    public function markAsWaitingPayment(): self
    {
        $this->status = self::STATUS_WAITING_PAYMENT;

        return $this;
    }

    public function markAsWaitingApproval(): self
    {
        $this->status = self::STATUS_WAITING_APPROVAL;

        DomainEventPublisher::instance()->publish(
            new BookingStatusChangedToWaitingApprovalEvent($this)
        );

        return $this;
    }

    public function getStatus(): string
    {
        return (string) $this->status;
    }

    public function getIsBookingFree(): bool
    {
        return $this->isBookingFree;
    }

    public function getIsNotRefundedYet(): bool
    {
        return $this->isNotRefundedYet;
    }

    public function getPaymentAmountAsFloat(): float
    {
        return (float) bcdiv($this->paymentAmount / 100, 1, 2);
    }

    public function getPaymentPerSlotAsFloat(): float
    {
        return (float) bcdiv($this->paymentPerSlot / 100, 1, 2);
    }

    public function getCreatedAt(): \DateTime
    {
        return clone $this->createdAt;
    }

    public function getCancelledAt(): ?\DateTime
    {
        return $this->cancelledAt
            ? clone $this->cancelledAt
            : null;
    }

    public function getPlacesCount(): int
    {
        return $this->placesCount;
    }

    public function getResident(): ?Resident
    {
        return $this->resident;
    }

    public function getIsCancelledByAdmin(): bool
    {
        return $this->isCancelledByAdmin;
    }

    public function getCancellationReason(): string
    {
        return $this->cancellationReason;
    }

    public function getIsRefundable(): bool
    {
        return $this->isRefundable;
    }

    public function getTimeSlots(): iterable
    {
        return (new ArrayCollection($this->timeSlots->toArray()))
            ->matching(Criteria::create()->orderBy(['timeFrom' => 'asc']))
            ->matching(Criteria::create()->orderBy(['date' => 'asc']));
    }

    public function cancel(
        bool $isCancelledByAdmin,
        string $reason,
        bool $isCheckEndBookingDateTime = false
    ): self {
        if ($this->isCancelled()) {
            return $this;
        }

        if (self::STATUS_APPROVAL_DECLINED === $this->status ||
            ($isCheckEndBookingDateTime && new \DateTime() >= $this->endBookingDateTime)
        ) {
            throw new BookingCanNotBeCancelledException();
        }

        $this->isCancelledByAdmin = $isCancelledByAdmin;
        $this->cancelledAt = new \DateTime();
        $this->status = self::STATUS_CANCELLED;
        $this->cancellationReason = $isCancelledByAdmin
            ? $reason
            : '';

        DomainEventPublisher::instance()->publish(new BookingCancelledEvent($this));

        return $this;
    }

    public function isCancelled(): bool
    {
        return self::STATUS_CANCELLED === $this->status;
    }

    public function isDeclined(): bool
    {
        return self::STATUS_APPROVAL_DECLINED === $this->status;
    }

    public function isGranted(): bool
    {
        return self::STATUS_APPROVAL_GRANTED === $this->status;
    }

    public function isWaitingApproval(): bool
    {
        return self::STATUS_WAITING_APPROVAL === $this->status;
    }

    public function isWaitingPayment(): bool
    {
        return self::STATUS_WAITING_PAYMENT === $this->status;
    }

    public function getFirstBookingDate(): \DateTime
    {
        return clone $this->firstBookingDate;
    }

    public function getFirstBookingDateTime(): \DateTime
    {
        return clone $this->firstBookingDateTime;
    }

    public function getAmenity(): ?Amenity
    {
        return $this->timeSlots->first()->getAmenity();
    }

    public function getPaymentAccountId(): string
    {
        return $this->getAmenity()
            ? $this->getAmenity()->getCondo()->getPaymentAccountId()
            : '';
    }

    public function isAdministrationBooking(): bool
    {
        return !(bool) $this->resident;
    }

    public function getAdministrationBookingStartDate(): ?\DateTime
    {
        return $this->administrationBookingStartDate
            ? clone $this->administrationBookingStartDate
            : null;
    }

    public function getAdministrationBookingEndDate(): ?\DateTime
    {
        return $this->administrationBookingEndDate
            ? clone $this->administrationBookingEndDate
            : null;
    }

    public function approve(): self
    {
        if ($this->isGranted()) {
            return $this;
        }

        if (!$this->isWaitingApproval()) {
            throw new BookingCanNotBeApprovedException();
        }

        $this->status = self::STATUS_APPROVAL_GRANTED;
        DomainEventPublisher::instance()->publish(new BookingApprovedEvent($this));

        return $this;
    }

    public function decline(): self
    {
        if ($this->isDeclined()) {
            return $this;
        }

        if (!$this->isWaitingApproval()) {
            throw new BookingCanNotBeDeclinedException();
        }

        $this->status = self::STATUS_APPROVAL_DECLINED;
        DomainEventPublisher::instance()->publish(new BookingDeclinedEvent($this));

        return $this;
    }

    public function getIsTemporaryPaid(): bool
    {
        return $this->isTemporaryPaid;
    }

    public function getIsPaymentTimeExceeded(): bool
    {
        return $this->isPaymentTimeExceeded;
    }

    public function refund(bool $isRefundedByAdmin): self
    {
        if ($isRefundedByAdmin && !$this->isCanBeRefunded()) {
            throw new BookingCanNotBeMarkedAsRefundedException();
        }

        if ($isRefundedByAdmin) {
            $this->refundedByAdminAt = new \DateTime();
            $this->isNotRefundedYet = false;
        } else {
            $this->refundedByPaymentProviderAt = new \DateTime();
            if (!$this->isPaidByCash() || ($this->isPaidByCash() && $this->refundedByAdminAt)) {
                $this->isNotRefundedYet = false;
            }
        }

        return $this;
    }

    public function isCanBeRefunded(): bool
    {
        return $this->isCancelled()
            && $this->isRefundable
            && $this->isNotRefundedYet;
    }

    public function markAsTemporaryPaid(): self
    {
        $this->isTemporaryPaid = true;

        return $this;
    }

    public function markAsPaymentTimeExceeded(): self
    {
        $this->isPaymentTimeExceeded = true;

        return $this;
    }

    public function getIsCreatedByResident(): bool
    {
        return $this->isCreatedByResident;
    }

    public function pay(bool $isPaidByAdmin): self
    {
        if (!$isPaidByAdmin) {
            // todo check paid amount === $this->amount

            $this->isRefundable = true;
            $this->isNotRefundedYet = true;
            $this->paidByCardAt = new \DateTime();

            if ($this->isWaitingPayment()) {
                $this->markAsCompleted();
            }

            return $this;
        }

        if (!$this->isCanBePaid()) {
            throw new BookingCanNotBeMarkedAsPaidException();
        }

        $this->isRefundable = true;
        $this->isNotRefundedYet = true;
        $this->paidByCashAt = new \DateTime();

        $this->markAsCompleted();

        return $this;
    }

    public function isCanBePaid(): bool
    {
        return $this->isWaitingPayment()/* && !$this->isTemporaryPaid*/
            ;
    }

    public function getPaidByCardAt(): ?\DateTime
    {
        return $this->paidByCardAt
            ? clone $this->paidByCardAt
            : null;
    }

    public function getPaidByCashAt(): ?\DateTime
    {
        return $this->paidByCashAt
            ? clone $this->paidByCashAt
            : null;
    }

    public function isPaidByCash(): bool
    {
        return (bool) $this->paidByCashAt;
    }

    public function isPaidByCard(): bool
    {
        return (bool) $this->paidByCardAt;
    }

    public function isRefundedByAdmin(): bool
    {
        return (bool) $this->refundedByAdminAt;
    }

    public function isRefundedByPaymentProvider(): bool
    {
        return (bool) $this->refundedByPaymentProviderAt;
    }

    public function getBookingPeriod(): string
    {
        $start = $this->firstBookingDate->format('M d, Y');
        $end = $this->endBookingDateTime->format('M d, Y');
        if ($start === $end) {
            return $start;
        }

        return $start.' - '.$end;
    }

    /**
     * @return Transaction
     */
    public function getTransaction(): ?Transaction
    {
        return $this->transactions->first();
    }
}
