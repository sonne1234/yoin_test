<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_f4ab8a06bf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_f4ab8a062989f1fd", columns={"invoice_id"}), @ORM\Index(name="idx_f4ab8a063301c60", columns={"booking_id"}), @ORM\Index(name="idx_f4ab8a06e2b100ed", columns={"condo_id"}), @ORM\Index(name="idx_f4ab8a068012c5b0", columns={"resident_id"})})
 * @ORM\Entity
 */
class Transaction
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="transaction_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="transactionid", type="string", length=255, nullable=false)
     */
    private $transactionid;

    /**
     * @var string
     *
     * @ORM\Column(name="serviceid", type="string", length=255, nullable=false)
     */
    private $serviceid;

    /**
     * @var string
     *
     * @ORM\Column(name="servicename", type="string", length=255, nullable=false)
     */
    private $servicename;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", precision=10, scale=0, nullable=false)
     */
    private $amount;

    /**
     * @var float
     *
     * @ORM\Column(name="bankfee", type="float", precision=10, scale=0, nullable=false)
     */
    private $bankfee;

    /**
     * @var float
     *
     * @ORM\Column(name="utilityfee", type="float", precision=10, scale=0, nullable=false)
     */
    private $utilityfee;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", precision=10, scale=0, nullable=false)
     */
    private $total;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var string|null
     *
     * @ORM\Column(name="rawextradata", type="string", length=255, nullable=true)
     */
    private $rawextradata;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime", nullable=false)
     */
    private $createdat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedat", type="datetime", nullable=false)
     */
    private $updatedat;

    /**
     * @var bool
     *
     * @ORM\Column(name="isyointransaction", type="boolean", nullable=false)
     */
    private $isyointransaction;

    /**
     * @var \Invoice
     *
     * @ORM\ManyToOne(targetEntity="Invoice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="invoice_id", referencedColumnName="id")
     * })
     */
    private $invoice;

    /**
     * @var \Booking
     *
     * @ORM\ManyToOne(targetEntity="Booking")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="booking_id", referencedColumnName="id")
     * })
     */
    private $booking;

    /**
     * @var \Useridentity
     *
     * @ORM\ManyToOne(targetEntity="Useridentity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resident_id", referencedColumnName="id")
     * })
     */
    private $resident;

    /**
     * @var \Condo
     *
     * @ORM\ManyToOne(targetEntity="Condo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="condo_id", referencedColumnName="id")
     * })
     */
    private $condo;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTransactionid(): ?string
    {
        return $this->transactionid;
    }

    public function setTransactionid(string $transactionid): self
    {
        $this->transactionid = $transactionid;

        return $this;
    }

    public function getServiceid(): ?string
    {
        return $this->serviceid;
    }

    public function setServiceid(string $serviceid): self
    {
        $this->serviceid = $serviceid;

        return $this;
    }

    public function getServicename(): ?string
    {
        return $this->servicename;
    }

    public function setServicename(string $servicename): self
    {
        $this->servicename = $servicename;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getBankfee(): ?float
    {
        return $this->bankfee;
    }

    public function setBankfee(float $bankfee): self
    {
        $this->bankfee = $bankfee;

        return $this;
    }

    public function getUtilityfee(): ?float
    {
        return $this->utilityfee;
    }

    public function setUtilityfee(float $utilityfee): self
    {
        $this->utilityfee = $utilityfee;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getRawextradata(): ?string
    {
        return $this->rawextradata;
    }

    public function setRawextradata(?string $rawextradata): self
    {
        $this->rawextradata = $rawextradata;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(\DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getUpdatedat(): ?\DateTimeInterface
    {
        return $this->updatedat;
    }

    public function setUpdatedat(\DateTimeInterface $updatedat): self
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    public function getIsyointransaction(): ?bool
    {
        return $this->isyointransaction;
    }

    public function setIsyointransaction(bool $isyointransaction): self
    {
        $this->isyointransaction = $isyointransaction;

        return $this;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(?Booking $booking): self
    {
        $this->booking = $booking;

        return $this;
    }

    public function getResident(): ?Useridentity
    {
        return $this->resident;
    }

    public function setResident(?Useridentity $resident): self
    {
        $this->resident = $resident;

        return $this;
    }

    public function getCondo(): ?Condo
    {
        return $this->condo;
    }

    public function setCondo(?Condo $condo): self
    {
        $this->condo = $condo;

        return $this;
    }


}
