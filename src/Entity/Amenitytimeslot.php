<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Amenitytimeslot
 *
 * @ORM\Table(name="amenitytimeslot", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_c2f2327cbf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_c2f2327c9f9f1305", columns={"amenity_id"})})
 * @ORM\Entity
 */
class Amenitytimeslot
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="amenitytimeslot_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetimetz", nullable=false)
     */
    private $date;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="timefrom", type="datetimetz", nullable=true)
     */
    private $timefrom;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="timetill", type="datetimetz", nullable=true)
     */
    private $timetill;

    /**
     * @var int
     *
     * @ORM\Column(name="freeplacescount", type="integer", nullable=false)
     */
    private $freeplacescount;

    /**
     * @var int
     *
     * @ORM\Column(name="bookedplacescount", type="integer", nullable=false)
     */
    private $bookedplacescount;

    /**
     * @var string
     *
     * @ORM\Column(name="dayofweek", type="string", length=255, nullable=false)
     */
    private $dayofweek;

    /**
     * @var bool
     *
     * @ORM\Column(name="isbookedbyadministration", type="boolean", nullable=false)
     */
    private $isbookedbyadministration;

    /**
     * @var bool
     *
     * @ORM\Column(name="isold", type="boolean", nullable=false)
     */
    private $isold;

    /**
     * @var int|null
     *
     * @ORM\Column(name="bookedtimeslotscount", type="integer", nullable=true)
     */
    private $bookedtimeslotscount;

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
     * @ORM\ManyToMany(targetEntity="Booking", mappedBy="amenitytimeslot")
     */
    private $booking;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->booking = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTimefrom(): ?\DateTimeInterface
    {
        return $this->timefrom;
    }

    public function setTimefrom(?\DateTimeInterface $timefrom): self
    {
        $this->timefrom = $timefrom;

        return $this;
    }

    public function getTimetill(): ?\DateTimeInterface
    {
        return $this->timetill;
    }

    public function setTimetill(?\DateTimeInterface $timetill): self
    {
        $this->timetill = $timetill;

        return $this;
    }

    public function getFreeplacescount(): ?int
    {
        return $this->freeplacescount;
    }

    public function setFreeplacescount(int $freeplacescount): self
    {
        $this->freeplacescount = $freeplacescount;

        return $this;
    }

    public function getBookedplacescount(): ?int
    {
        return $this->bookedplacescount;
    }

    public function setBookedplacescount(int $bookedplacescount): self
    {
        $this->bookedplacescount = $bookedplacescount;

        return $this;
    }

    public function getDayofweek(): ?string
    {
        return $this->dayofweek;
    }

    public function setDayofweek(string $dayofweek): self
    {
        $this->dayofweek = $dayofweek;

        return $this;
    }

    public function getIsbookedbyadministration(): ?bool
    {
        return $this->isbookedbyadministration;
    }

    public function setIsbookedbyadministration(bool $isbookedbyadministration): self
    {
        $this->isbookedbyadministration = $isbookedbyadministration;

        return $this;
    }

    public function getIsold(): ?bool
    {
        return $this->isold;
    }

    public function setIsold(bool $isold): self
    {
        $this->isold = $isold;

        return $this;
    }

    public function getBookedtimeslotscount(): ?int
    {
        return $this->bookedtimeslotscount;
    }

    public function setBookedtimeslotscount(?int $bookedtimeslotscount): self
    {
        $this->bookedtimeslotscount = $bookedtimeslotscount;

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
     * @return Collection|Booking[]
     */
    public function getBooking(): Collection
    {
        return $this->booking;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->booking->contains($booking)) {
            $this->booking[] = $booking;
            $booking->addAmenitytimeslot($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->booking->contains($booking)) {
            $this->booking->removeElement($booking);
            $booking->removeAmenitytimeslot($this);
        }

        return $this;
    }

}
