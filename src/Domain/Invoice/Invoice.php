<?php

namespace App\Domain\Invoice;

use App\Domain\DomainEventPublisher;
use App\Domain\GetEntityByIdInCollectionTrait;
use App\Domain\Invoice\Event\MaintenanceFeeInvoicePaidEvent;
use App\Domain\Invoice\Exception\InvalidPaymentAmountException;
use App\Domain\Invoice\Exception\InvoiceAlreadyPaidException;
use App\Domain\Invoice\Exception\InvoiceIsPendingPaymentException;
use App\Domain\ToArrayTransformTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"maintenance" = "MaintenanceFeeInvoice"})
 */
abstract class Invoice
{
    use
        GetEntityByIdInCollectionTrait, ToArrayTransformTrait;

    const PAYMENT_PENDING_OFFSET_SECONDS = 15 * 60;

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
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $updatedAt;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $amount;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $paidAt;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isPaid;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isPaidByCash;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $markedPendingPaymentAt;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->isPaid = false;
        $this->isPaidByCash = false;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAmount(): int
    {
        return $this->amount;
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

    public function markAsPaidByCash(): self
    {
        if (($timestamp = $this->getMarkedPendingPaymentAt())
            && ($timestamp->modify('+'.self::PAYMENT_PENDING_OFFSET_SECONDS.' seconds')) > (new \DateTime())
        ) {
            throw new InvoiceIsPendingPaymentException();
        } else {
            $this->isPaidByCash = true;
            $this->pay($this->amount);
        }

        return $this;
    }

    public function isPaid(): bool
    {
        return $this->isPaid;
    }

    public function isPaidByCash(): bool
    {
        return $this->isPaidByCash;
    }

    public function markPendingPayment(): self
    {
        $this->markedPendingPaymentAt = new \DateTime();

        return $this;
    }

    public function getMarkedPendingPaymentAt(): ?\DateTime
    {
        return $this->markedPendingPaymentAt ? clone $this->markedPendingPaymentAt : null;
    }

    public function getIsPaid(): bool
    {
        return $this->isPaid();
    }

    /**
     * @param int $amount in cents
     *
     * @return Invoice
     */
    public function pay(int $amount): self
    {
        if ($this->isPaid) {
            return $this;
        }
        $this->isPaid = true;
        $this->paidAt = new \DateTime();
        DomainEventPublisher::instance()->publish(
            new MaintenanceFeeInvoicePaidEvent($this)
        );

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPaidAt(): ?\DateTime
    {
        return $this->paidAt ? clone $this->paidAt : null;
    }
}
