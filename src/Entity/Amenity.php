<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Amenity
 *
 * @ORM\Table(name="amenity", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_64dd40ffbf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_64dd40ffe2b100ed", columns={"condo_id"}), @ORM\Index(name="idx_64dd40ff9a068af8", columns={"whocanbook_id"})})
 * @ORM\Entity
 */
class Amenity
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="amenity_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime", nullable=false)
     */
    private $createdat;

    /**
     * @var bool
     *
     * @ORM\Column(name="isobservable", type="boolean", nullable=false)
     */
    private $isobservable;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_name", type="string", length=255, nullable=false)
     */
    private $generaldataName;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_phonenumber", type="string", length=255, nullable=false)
     */
    private $generaldataPhonenumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="generaldata_namelowercase", type="string", length=255, nullable=true)
     */
    private $generaldataNamelowercase;

    /**
     * @var int
     *
     * @ORM\Column(name="generaldata_category", type="integer", nullable=false)
     */
    private $generaldataCategory;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_address", type="text", nullable=false)
     */
    private $generaldataAddress;

    /**
     * @var int
     *
     * @ORM\Column(name="generaldata_capacity", type="integer", nullable=false)
     */
    private $generaldataCapacity;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_description", type="text", nullable=false)
     */
    private $generaldataDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_rules", type="text", nullable=false)
     */
    private $generaldataRules;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_mainimageid", type="string", length=255, nullable=false)
     */
    private $generaldataMainimageid;

    /**
     * @var bool
     *
     * @ORM\Column(name="availabilitydata_isdifferentforalldays", type="boolean", nullable=false)
     */
    private $availabilitydataIsdifferentforalldays;

    /**
     * @var array
     *
     * @ORM\Column(name="availabilitydata_days", type="array", nullable=false)
     */
    private $availabilitydataDays;

    /**
     * @var array
     *
     * @ORM\Column(name="availabilitydata_daysprocessed", type="array", nullable=false)
     */
    private $availabilitydataDaysprocessed;

    /**
     * @var bool
     *
     * @ORM\Column(name="bookingdata_isbookingrequiresapproval", type="boolean", nullable=false)
     */
    private $bookingdataIsbookingrequiresapproval;

    /**
     * @var int
     *
     * @ORM\Column(name="bookingdata_limitfuturebookingmonths", type="integer", nullable=false)
     */
    private $bookingdataLimitfuturebookingmonths;

    /**
     * @var string
     *
     * @ORM\Column(name="bookingdata_bookingtimeoption", type="string", length=255, nullable=false)
     */
    private $bookingdataBookingtimeoption;

    /**
     * @var int
     *
     * @ORM\Column(name="bookingdata_timeslotduration", type="integer", nullable=false)
     */
    private $bookingdataTimeslotduration;

    /**
     * @var int
     *
     * @ORM\Column(name="bookingdata_countoftimeslotsforonebooking", type="integer", nullable=false)
     */
    private $bookingdataCountoftimeslotsforonebooking;

    /**
     * @var bool
     *
     * @ORM\Column(name="bookingdata_isparallelbookingallowed", type="boolean", nullable=false)
     */
    private $bookingdataIsparallelbookingallowed;

    /**
     * @var bool
     *
     * @ORM\Column(name="bookingdata_isamenitypaid", type="boolean", nullable=false)
     */
    private $bookingdataIsamenitypaid;

    /**
     * @var string
     *
     * @ORM\Column(name="bookingdata_paymentforoption", type="string", length=255, nullable=false)
     */
    private $bookingdataPaymentforoption;

    /**
     * @var int
     *
     * @ORM\Column(name="bookingdata_paymenttimeslotfee", type="integer", nullable=false)
     */
    private $bookingdataPaymenttimeslotfee;

    /**
     * @var int
     *
     * @ORM\Column(name="bookingdata_limitbookingpertimeslot", type="integer", nullable=false)
     */
    private $bookingdataLimitbookingpertimeslot;

    /**
     * @var int
     *
     * @ORM\Column(name="bookingdata_limitusersperbooking", type="integer", nullable=false)
     */
    private $bookingdataLimitusersperbooking;

    /**
     * @var \Condobuilding
     *
     * @ORM\ManyToOne(targetEntity="Condobuilding")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="whocanbook_id", referencedColumnName="id")
     * })
     */
    private $whocanbook;

    /**
     * @var \Condo
     *
     * @ORM\ManyToOne(targetEntity="Condo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="condo_id", referencedColumnName="id")
     * })
     */
    private $condo;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Image", inversedBy="amenity")
     * @ORM\JoinTable(name="amenity_image",
     *   joinColumns={
     *     @ORM\JoinColumn(name="amenity_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     *   }
     * )
     */
    private $image;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->image = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getIsobservable(): ?bool
    {
        return $this->isobservable;
    }

    public function setIsobservable(bool $isobservable): self
    {
        $this->isobservable = $isobservable;

        return $this;
    }

    public function getGeneraldataName(): ?string
    {
        return $this->generaldataName;
    }

    public function setGeneraldataName(string $generaldataName): self
    {
        $this->generaldataName = $generaldataName;

        return $this;
    }

    public function getGeneraldataPhonenumber(): ?string
    {
        return $this->generaldataPhonenumber;
    }

    public function setGeneraldataPhonenumber(string $generaldataPhonenumber): self
    {
        $this->generaldataPhonenumber = $generaldataPhonenumber;

        return $this;
    }

    public function getGeneraldataNamelowercase(): ?string
    {
        return $this->generaldataNamelowercase;
    }

    public function setGeneraldataNamelowercase(?string $generaldataNamelowercase): self
    {
        $this->generaldataNamelowercase = $generaldataNamelowercase;

        return $this;
    }

    public function getGeneraldataCategory(): ?int
    {
        return $this->generaldataCategory;
    }

    public function setGeneraldataCategory(int $generaldataCategory): self
    {
        $this->generaldataCategory = $generaldataCategory;

        return $this;
    }

    public function getGeneraldataAddress(): ?string
    {
        return $this->generaldataAddress;
    }

    public function setGeneraldataAddress(string $generaldataAddress): self
    {
        $this->generaldataAddress = $generaldataAddress;

        return $this;
    }

    public function getGeneraldataCapacity(): ?int
    {
        return $this->generaldataCapacity;
    }

    public function setGeneraldataCapacity(int $generaldataCapacity): self
    {
        $this->generaldataCapacity = $generaldataCapacity;

        return $this;
    }

    public function getGeneraldataDescription(): ?string
    {
        return $this->generaldataDescription;
    }

    public function setGeneraldataDescription(string $generaldataDescription): self
    {
        $this->generaldataDescription = $generaldataDescription;

        return $this;
    }

    public function getGeneraldataRules(): ?string
    {
        return $this->generaldataRules;
    }

    public function setGeneraldataRules(string $generaldataRules): self
    {
        $this->generaldataRules = $generaldataRules;

        return $this;
    }

    public function getGeneraldataMainimageid(): ?string
    {
        return $this->generaldataMainimageid;
    }

    public function setGeneraldataMainimageid(string $generaldataMainimageid): self
    {
        $this->generaldataMainimageid = $generaldataMainimageid;

        return $this;
    }

    public function getAvailabilitydataIsdifferentforalldays(): ?bool
    {
        return $this->availabilitydataIsdifferentforalldays;
    }

    public function setAvailabilitydataIsdifferentforalldays(bool $availabilitydataIsdifferentforalldays): self
    {
        $this->availabilitydataIsdifferentforalldays = $availabilitydataIsdifferentforalldays;

        return $this;
    }

    public function getAvailabilitydataDays(): ?array
    {
        return $this->availabilitydataDays;
    }

    public function setAvailabilitydataDays(array $availabilitydataDays): self
    {
        $this->availabilitydataDays = $availabilitydataDays;

        return $this;
    }

    public function getAvailabilitydataDaysprocessed(): ?array
    {
        return $this->availabilitydataDaysprocessed;
    }

    public function setAvailabilitydataDaysprocessed(array $availabilitydataDaysprocessed): self
    {
        $this->availabilitydataDaysprocessed = $availabilitydataDaysprocessed;

        return $this;
    }

    public function getBookingdataIsbookingrequiresapproval(): ?bool
    {
        return $this->bookingdataIsbookingrequiresapproval;
    }

    public function setBookingdataIsbookingrequiresapproval(bool $bookingdataIsbookingrequiresapproval): self
    {
        $this->bookingdataIsbookingrequiresapproval = $bookingdataIsbookingrequiresapproval;

        return $this;
    }

    public function getBookingdataLimitfuturebookingmonths(): ?int
    {
        return $this->bookingdataLimitfuturebookingmonths;
    }

    public function setBookingdataLimitfuturebookingmonths(int $bookingdataLimitfuturebookingmonths): self
    {
        $this->bookingdataLimitfuturebookingmonths = $bookingdataLimitfuturebookingmonths;

        return $this;
    }

    public function getBookingdataBookingtimeoption(): ?string
    {
        return $this->bookingdataBookingtimeoption;
    }

    public function setBookingdataBookingtimeoption(string $bookingdataBookingtimeoption): self
    {
        $this->bookingdataBookingtimeoption = $bookingdataBookingtimeoption;

        return $this;
    }

    public function getBookingdataTimeslotduration(): ?int
    {
        return $this->bookingdataTimeslotduration;
    }

    public function setBookingdataTimeslotduration(int $bookingdataTimeslotduration): self
    {
        $this->bookingdataTimeslotduration = $bookingdataTimeslotduration;

        return $this;
    }

    public function getBookingdataCountoftimeslotsforonebooking(): ?int
    {
        return $this->bookingdataCountoftimeslotsforonebooking;
    }

    public function setBookingdataCountoftimeslotsforonebooking(int $bookingdataCountoftimeslotsforonebooking): self
    {
        $this->bookingdataCountoftimeslotsforonebooking = $bookingdataCountoftimeslotsforonebooking;

        return $this;
    }

    public function getBookingdataIsparallelbookingallowed(): ?bool
    {
        return $this->bookingdataIsparallelbookingallowed;
    }

    public function setBookingdataIsparallelbookingallowed(bool $bookingdataIsparallelbookingallowed): self
    {
        $this->bookingdataIsparallelbookingallowed = $bookingdataIsparallelbookingallowed;

        return $this;
    }

    public function getBookingdataIsamenitypaid(): ?bool
    {
        return $this->bookingdataIsamenitypaid;
    }

    public function setBookingdataIsamenitypaid(bool $bookingdataIsamenitypaid): self
    {
        $this->bookingdataIsamenitypaid = $bookingdataIsamenitypaid;

        return $this;
    }

    public function getBookingdataPaymentforoption(): ?string
    {
        return $this->bookingdataPaymentforoption;
    }

    public function setBookingdataPaymentforoption(string $bookingdataPaymentforoption): self
    {
        $this->bookingdataPaymentforoption = $bookingdataPaymentforoption;

        return $this;
    }

    public function getBookingdataPaymenttimeslotfee(): ?int
    {
        return $this->bookingdataPaymenttimeslotfee;
    }

    public function setBookingdataPaymenttimeslotfee(int $bookingdataPaymenttimeslotfee): self
    {
        $this->bookingdataPaymenttimeslotfee = $bookingdataPaymenttimeslotfee;

        return $this;
    }

    public function getBookingdataLimitbookingpertimeslot(): ?int
    {
        return $this->bookingdataLimitbookingpertimeslot;
    }

    public function setBookingdataLimitbookingpertimeslot(int $bookingdataLimitbookingpertimeslot): self
    {
        $this->bookingdataLimitbookingpertimeslot = $bookingdataLimitbookingpertimeslot;

        return $this;
    }

    public function getBookingdataLimitusersperbooking(): ?int
    {
        return $this->bookingdataLimitusersperbooking;
    }

    public function setBookingdataLimitusersperbooking(int $bookingdataLimitusersperbooking): self
    {
        $this->bookingdataLimitusersperbooking = $bookingdataLimitusersperbooking;

        return $this;
    }

    public function getWhocanbook(): ?Condobuilding
    {
        return $this->whocanbook;
    }

    public function setWhocanbook(?Condobuilding $whocanbook): self
    {
        $this->whocanbook = $whocanbook;

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

    /**
     * @return Collection|Image[]
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Image $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->image->contains($image)) {
            $this->image->removeElement($image);
        }

        return $this;
    }

}
