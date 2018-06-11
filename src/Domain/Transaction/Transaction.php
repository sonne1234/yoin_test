<?php

namespace App\Domain\Transaction;

use App\Domain\Booking\Booking;
use App\Domain\Condo\Condo;
use App\Domain\DomainEventPublisher;
use App\Domain\GetEntityByIdInCollectionTrait;
use App\Domain\Invoice\Event\MaintenanceFeeInvoicePaidEvent;
use App\Domain\Invoice\Exception\InvoiceIsPendingPaymentException;
use App\Domain\Invoice\MaintenanceFeeInvoice;
use App\Domain\Resident\Resident;
use App\Domain\ToArrayTransformTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class Transaction
{
    use
        GetEntityByIdInCollectionTrait, ToArrayTransformTrait;

    const STATUS_PAID = 'Pago Realizado';

    const YOIN_PAYMENT_IDS = [267, 277];

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
    private $transactionId;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $serviceId;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $serviceName;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=false)
     */
    protected $amount;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=false)
     */
    private $bankFee;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=false)
     */
    private $utilityFee;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=false)
     */
    private $total;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $status;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $date;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $rawExtraData;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $updatedAt;

    /**
     * @var Condo|null
     * @ORM\ManyToOne(targetEntity="App\Domain\Condo\Condo", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $condo;

    /**
     * @var Resident
     * @ORM\ManyToOne(targetEntity="App\Domain\Resident\Resident", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $resident;

    /**
     * @var Booking
     * @ORM\ManyToOne(targetEntity="App\Domain\Booking\Booking", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $booking;

    /**
     * @var MaintenanceFeeInvoice
     * @ORM\ManyToOne(targetEntity="App\Domain\Invoice\MaintenanceFeeInvoice", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $invoice;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isYoinTransaction;

    public function __construct(
        string $transactionId,
        Resident $resident,
        string $serviceId,
        string $serviceName,
        ?Condo $condo,
        ?Booking $booking,
        ?MaintenanceFeeInvoice $invoice,
        float $amount,
        float $bankFee,
        float $utilityFee,
        float $total,
        string $status,
        \DateTime $date,
        ?string $rawExtraData
    ) {

        $this->id = Uuid::uuid4()->toString();

        $this->transactionId = $transactionId;
        $this->resident = $resident;
        $this->serviceId = $serviceId;
        $this->serviceName = $serviceName;
        $this->condo = $condo;
        $this->booking = $booking;
        $this->invoice = $invoice;

        $this->amount = $amount;
        $this->bankFee = $bankFee;
        $this->utilityFee = $utilityFee;
        $this->total = $total;
        $this->status = $status;
        $this->date = $date;
        $this->rawExtraData = $rawExtraData;

        $this->isYoinTransaction = $this->isYoinTransaction();

        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return clone $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return clone $this->updatedAt;
    }

    public function isPaid(): bool
    {
        return $this->status == self::STATUS_PAID;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getYoinFee(): float
    {
        return $this->isYoinTransaction ? 0 : $this->utilityFee / 2;
    }

    public function isYoinTransaction(): bool
    {
        return (bool)($this->condo || in_array($this->serviceId, self::YOIN_PAYMENT_IDS));
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
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * @return float
     */
    public function getBankFee(): float
    {
        return $this->bankFee;
    }

    /**
     * @return float
     */
    public function getUtilityFee(): float
    {
        return $this->utilityFee;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @return Resident
     */
    public function getResident(): Resident
    {
        return $this->resident;
    }

    /**
     * @return Booking
     */
    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    /**
     * @return MaintenanceFeeInvoice
     */
    public function getInvoice(): ?MaintenanceFeeInvoice
    {
        return $this->invoice;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return clone $this->date;
    }
}
