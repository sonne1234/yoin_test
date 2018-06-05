<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invoice
 *
 * @ORM\Table(name="invoice", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_5fd82ed8bf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_5fd82ed8f8bd700d", columns={"unit_id"})})
 * @ORM\Entity
 */
class Invoice
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="invoice_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

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
     * @var int
     *
     * @ORM\Column(name="amount", type="integer", nullable=false)
     */
    private $amount;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="paidat", type="datetime", nullable=true)
     */
    private $paidat;

    /**
     * @var bool
     *
     * @ORM\Column(name="ispaid", type="boolean", nullable=false)
     */
    private $ispaid;

    /**
     * @var bool
     *
     * @ORM\Column(name="ispaidbycash", type="boolean", nullable=false)
     */
    private $ispaidbycash;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="markedpendingpaymentat", type="datetime", nullable=true)
     */
    private $markedpendingpaymentat;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="payperiod", type="date", nullable=true)
     */
    private $payperiod;

    /**
     * @var \Unit
     *
     * @ORM\ManyToOne(targetEntity="Unit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unit_id", referencedColumnName="id")
     * })
     */
    private $unit;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPaidat(): ?\DateTimeInterface
    {
        return $this->paidat;
    }

    public function setPaidat(?\DateTimeInterface $paidat): self
    {
        $this->paidat = $paidat;

        return $this;
    }

    public function getIspaid(): ?bool
    {
        return $this->ispaid;
    }

    public function setIspaid(bool $ispaid): self
    {
        $this->ispaid = $ispaid;

        return $this;
    }

    public function getIspaidbycash(): ?bool
    {
        return $this->ispaidbycash;
    }

    public function setIspaidbycash(bool $ispaidbycash): self
    {
        $this->ispaidbycash = $ispaidbycash;

        return $this;
    }

    public function getMarkedpendingpaymentat(): ?\DateTimeInterface
    {
        return $this->markedpendingpaymentat;
    }

    public function setMarkedpendingpaymentat(?\DateTimeInterface $markedpendingpaymentat): self
    {
        $this->markedpendingpaymentat = $markedpendingpaymentat;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPayperiod(): ?\DateTimeInterface
    {
        return $this->payperiod;
    }

    public function setPayperiod(?\DateTimeInterface $payperiod): self
    {
        $this->payperiod = $payperiod;

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }


}
