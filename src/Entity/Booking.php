<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Booking
 *
 * @ORM\Table(name="booking", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_2fb1d442bf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_2fb1d4428012c5b0", columns={"resident_id"}), @ORM\Index(name="idx_2fb1d4423174800f", columns={"createdby_id"}), @ORM\Index(name="idx_2fb1d4429f9f1305", columns={"amenity_id"})})
 * @ORM\Entity
 */
class Booking
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="booking_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetimetz", nullable=false)
     */
    private $createdat;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="cancelledat", type="datetimetz", nullable=true)
     */
    private $cancelledat;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="placescount", type="integer", nullable=false)
     */
    private $placescount;

    /**
     * @var int
     *
     * @ORM\Column(name="paymentamount", type="integer", nullable=false)
     */
    private $paymentamount;

    /**
     * @var int
     *
     * @ORM\Column(name="paymentperslot", type="integer", nullable=false)
     */
    private $paymentperslot;

    /**
     * @var int
     *
     * @ORM\Column(name="timeslotscount", type="integer", nullable=false)
     */
    private $timeslotscount;

    /**
     * @var string
     *
     * @ORM\Column(name="cancellationreason", type="string", length=255, nullable=false)
     */
    private $cancellationreason;

    /**
     * @var bool
     *
     * @ORM\Column(name="isbookingfree", type="boolean", nullable=false)
     */
    private $isbookingfree;

    /**
     * @var bool
     *
     * @ORM\Column(name="isnotrefundedyet", type="boolean", nullable=false)
     */
    private $isnotrefundedyet;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="refundedbypaymentproviderat", type="datetimetz", nullable=true)
     */
    private $refundedbypaymentproviderat;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="refundedbyadminat", type="datetimetz", nullable=true)
     */
    private $refundedbyadminat;

    /**
     * @var bool
     *
     * @ORM\Column(name="isrefundable", type="boolean", nullable=false)
     */
    private $isrefundable;

    /**
     * @var bool
     *
     * @ORM\Column(name="iscancelledbyadmin", type="boolean", nullable=false)
     */
    private $iscancelledbyadmin;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="paidbycardat", type="datetimetz", nullable=true)
     */
    private $paidbycardat;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="paidbycashat", type="datetimetz", nullable=true)
     */
    private $paidbycashat;

    /**
     * @var bool
     *
     * @ORM\Column(name="istemporarypaid", type="boolean", nullable=false)
     */
    private $istemporarypaid;

    /**
     * @var bool
     *
     * @ORM\Column(name="ispaymenttimeexceeded", type="boolean", nullable=false)
     */
    private $ispaymenttimeexceeded;

    /**
     * @var bool
     *
     * @ORM\Column(name="iscreatedbyresident", type="boolean", nullable=false)
     */
    private $iscreatedbyresident;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="firstbookingdate", type="datetimetz", nullable=false)
     */
    private $firstbookingdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="firstbookingdatetime", type="datetimetz", nullable=false)
     */
    private $firstbookingdatetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endbookingdatetime", type="datetimetz", nullable=false)
     */
    private $endbookingdatetime;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="administrationbookingstartdate", type="datetimetz", nullable=true)
     */
    private $administrationbookingstartdate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="administrationbookingenddate", type="datetimetz", nullable=true)
     */
    private $administrationbookingenddate;

    /**
     * @var \Useridentity
     *
     * @ORM\ManyToOne(targetEntity="Useridentity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="createdby_id", referencedColumnName="id")
     * })
     */
    private $createdby;

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
     * @var \Amenity
     *
     * @ORM\ManyToOne(targetEntity="Amenity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="amenity_id", referencedColumnName="id")
     * })
     */
    private $amenity;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Amenitytimeslot", inversedBy="booking")
     * @ORM\JoinTable(name="booking_amenitytimeslot",
     *   joinColumns={
     *     @ORM\JoinColumn(name="booking_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="amenitytimeslot_id", referencedColumnName="id")
     *   }
     * )
     */
    private $amenitytimeslot;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->amenitytimeslot = new \Doctrine\Common\Collections\ArrayCollection();
    }

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

    public function getCancelledat(): ?\DateTimeInterface
    {
        return $this->cancelledat;
    }

    public function setCancelledat(?\DateTimeInterface $cancelledat): self
    {
        $this->cancelledat = $cancelledat;

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

    public function getPlacescount(): ?int
    {
        return $this->placescount;
    }

    public function setPlacescount(int $placescount): self
    {
        $this->placescount = $placescount;

        return $this;
    }

    public function getPaymentamount(): ?int
    {
        return $this->paymentamount;
    }

    public function setPaymentamount(int $paymentamount): self
    {
        $this->paymentamount = $paymentamount;

        return $this;
    }

    public function getPaymentperslot(): ?int
    {
        return $this->paymentperslot;
    }

    public function setPaymentperslot(int $paymentperslot): self
    {
        $this->paymentperslot = $paymentperslot;

        return $this;
    }

    public function getTimeslotscount(): ?int
    {
        return $this->timeslotscount;
    }

    public function setTimeslotscount(int $timeslotscount): self
    {
        $this->timeslotscount = $timeslotscount;

        return $this;
    }

    public function getCancellationreason(): ?string
    {
        return $this->cancellationreason;
    }

    public function setCancellationreason(string $cancellationreason): self
    {
        $this->cancellationreason = $cancellationreason;

        return $this;
    }

    public function getIsbookingfree(): ?bool
    {
        return $this->isbookingfree;
    }

    public function setIsbookingfree(bool $isbookingfree): self
    {
        $this->isbookingfree = $isbookingfree;

        return $this;
    }

    public function getIsnotrefundedyet(): ?bool
    {
        return $this->isnotrefundedyet;
    }

    public function setIsnotrefundedyet(bool $isnotrefundedyet): self
    {
        $this->isnotrefundedyet = $isnotrefundedyet;

        return $this;
    }

    public function getRefundedbypaymentproviderat(): ?\DateTimeInterface
    {
        return $this->refundedbypaymentproviderat;
    }

    public function setRefundedbypaymentproviderat(?\DateTimeInterface $refundedbypaymentproviderat): self
    {
        $this->refundedbypaymentproviderat = $refundedbypaymentproviderat;

        return $this;
    }

    public function getRefundedbyadminat(): ?\DateTimeInterface
    {
        return $this->refundedbyadminat;
    }

    public function setRefundedbyadminat(?\DateTimeInterface $refundedbyadminat): self
    {
        $this->refundedbyadminat = $refundedbyadminat;

        return $this;
    }

    public function getIsrefundable(): ?bool
    {
        return $this->isrefundable;
    }

    public function setIsrefundable(bool $isrefundable): self
    {
        $this->isrefundable = $isrefundable;

        return $this;
    }

    public function getIscancelledbyadmin(): ?bool
    {
        return $this->iscancelledbyadmin;
    }

    public function setIscancelledbyadmin(bool $iscancelledbyadmin): self
    {
        $this->iscancelledbyadmin = $iscancelledbyadmin;

        return $this;
    }

    public function getPaidbycardat(): ?\DateTimeInterface
    {
        return $this->paidbycardat;
    }

    public function setPaidbycardat(?\DateTimeInterface $paidbycardat): self
    {
        $this->paidbycardat = $paidbycardat;

        return $this;
    }

    public function getPaidbycashat(): ?\DateTimeInterface
    {
        return $this->paidbycashat;
    }

    public function setPaidbycashat(?\DateTimeInterface $paidbycashat): self
    {
        $this->paidbycashat = $paidbycashat;

        return $this;
    }

    public function getIstemporarypaid(): ?bool
    {
        return $this->istemporarypaid;
    }

    public function setIstemporarypaid(bool $istemporarypaid): self
    {
        $this->istemporarypaid = $istemporarypaid;

        return $this;
    }

    public function getIspaymenttimeexceeded(): ?bool
    {
        return $this->ispaymenttimeexceeded;
    }

    public function setIspaymenttimeexceeded(bool $ispaymenttimeexceeded): self
    {
        $this->ispaymenttimeexceeded = $ispaymenttimeexceeded;

        return $this;
    }

    public function getIscreatedbyresident(): ?bool
    {
        return $this->iscreatedbyresident;
    }

    public function setIscreatedbyresident(bool $iscreatedbyresident): self
    {
        $this->iscreatedbyresident = $iscreatedbyresident;

        return $this;
    }

    public function getFirstbookingdate(): ?\DateTimeInterface
    {
        return $this->firstbookingdate;
    }

    public function setFirstbookingdate(\DateTimeInterface $firstbookingdate): self
    {
        $this->firstbookingdate = $firstbookingdate;

        return $this;
    }

    public function getFirstbookingdatetime(): ?\DateTimeInterface
    {
        return $this->firstbookingdatetime;
    }

    public function setFirstbookingdatetime(\DateTimeInterface $firstbookingdatetime): self
    {
        $this->firstbookingdatetime = $firstbookingdatetime;

        return $this;
    }

    public function getEndbookingdatetime(): ?\DateTimeInterface
    {
        return $this->endbookingdatetime;
    }

    public function setEndbookingdatetime(\DateTimeInterface $endbookingdatetime): self
    {
        $this->endbookingdatetime = $endbookingdatetime;

        return $this;
    }

    public function getAdministrationbookingstartdate(): ?\DateTimeInterface
    {
        return $this->administrationbookingstartdate;
    }

    public function setAdministrationbookingstartdate(?\DateTimeInterface $administrationbookingstartdate): self
    {
        $this->administrationbookingstartdate = $administrationbookingstartdate;

        return $this;
    }

    public function getAdministrationbookingenddate(): ?\DateTimeInterface
    {
        return $this->administrationbookingenddate;
    }

    public function setAdministrationbookingenddate(?\DateTimeInterface $administrationbookingenddate): self
    {
        $this->administrationbookingenddate = $administrationbookingenddate;

        return $this;
    }

    public function getCreatedby(): ?Useridentity
    {
        return $this->createdby;
    }

    public function setCreatedby(?Useridentity $createdby): self
    {
        $this->createdby = $createdby;

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

    public function getAmenity(): ?Amenity
    {
        return $this->amenity;
    }

    public function setAmenity(?Amenity $amenity): self
    {
        $this->amenity = $amenity;

        return $this;
    }

    /**
     * @return Collection|Amenitytimeslot[]
     */
    public function getAmenitytimeslot(): Collection
    {
        return $this->amenitytimeslot;
    }

    public function addAmenitytimeslot(Amenitytimeslot $amenitytimeslot): self
    {
        if (!$this->amenitytimeslot->contains($amenitytimeslot)) {
            $this->amenitytimeslot[] = $amenitytimeslot;
        }

        return $this;
    }

    public function removeAmenitytimeslot(Amenitytimeslot $amenitytimeslot): self
    {
        if ($this->amenitytimeslot->contains($amenitytimeslot)) {
            $this->amenitytimeslot->removeElement($amenitytimeslot);
        }

        return $this;
    }

}
