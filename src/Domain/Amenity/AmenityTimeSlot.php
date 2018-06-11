<?php

namespace App\Domain\Amenity;

use App\Domain\Booking\Booking;
use App\Domain\GetEntityByIdInCollectionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class AmenityTimeSlot
{
    use GetEntityByIdInCollectionTrait;

    const TIMEZONE = 'America/Mexico_City';

    const TYPE_WHOLE_DAY = AmenityBookingData::BOOKING_TIME_ALL;
    const TYPE_TIME_ON_DEMAND = AmenityBookingData::BOOKING_TIME_DEMAND;

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
    private $type;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=false)
     */
    private $date;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $timeFrom;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $timeTill;

    /**
     * @var Amenity
     * @ORM\ManyToOne(targetEntity="Amenity", inversedBy="timeSlots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $amenity;

    /**
     * @ORM\ManyToMany(targetEntity="App\Domain\Booking\Booking", mappedBy="timeSlots")
     * @ORM\JoinTable(name="amenitytimeslot_booking")
     */
    private $bookings;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $freePlacesCount = 0;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $bookedPlacesCount = 0;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $dayOfWeek;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isBookedByAdministration = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isOld = false;

    /**
     * Only for special reasons for QUERIES from DoctrineAmenityTimeSlotRepository.
     *
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bookedTimeslotsCount;

    public function __construct(
        string $type,
        \DateTime $date,
        \DateTimeImmutable $timeFrom = null,
        \DateTimeImmutable $timeTill = null
    ) {
        if (!in_array($type, [self::TYPE_TIME_ON_DEMAND, self::TYPE_WHOLE_DAY])) {
            throw new \LogicException('Wrong type');
        }
        if (self::TYPE_WHOLE_DAY === $type) {
            if ($timeFrom || $timeTill) {
                throw new \LogicException('Wrong timeFrom or timeTill');
            }
        } else {
            if (!$timeFrom || !$timeTill) {
                throw new \LogicException('Wrong timeFrom or timeTill');
            }
        }

        $this->id = Uuid::uuid4()->toString();
        $this->type = $type;
        $this->date = clone $date;
        $this->bookings = new ArrayCollection();

        foreach (['timeFrom', 'timeTill'] as $field) {
            if ($$field) {
                $dt = new \DateTime(null, $$field->getTimezone());
                $dt->setTimestamp($$field->getTimestamp());
                $this->$field = $dt;
            } else {
                $this->$field = null;
            }
        }

        foreach (['date', 'timeFrom', 'timeTill'] as $field) {
            if ($this->$field) {
                $this->$field->setTimezone(new \DateTimeZone(self::TIMEZONE));
            }
        }

        $this->dayOfWeek = strtolower($this->date->format('l'));
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setAmenity(Amenity $amenity): self
    {
        $this->amenity = $amenity;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDate(): \DateTime
    {
        return clone $this->date;
    }

    public function getTimeFrom(): ?\DateTime
    {
        return $this->timeFrom
            ? clone $this->timeFrom
            : null;
    }

    public function getTimeFromFormatted(bool $asString = false)
    {
        if (!$this->timeFrom) {
            return null;
        }

        $res = $this->timeFrom->format('H:i');

        return $asString
            ? $res
            : [
                'hrs' => (int) explode(':', $res)[0],
                'min' => (int) explode(':', $res)[1],
            ];
    }

    public function getTimeTill(): ?\DateTime
    {
        return $this->timeTill
            ? clone $this->timeTill
            : null;
    }

    /**
     * @param bool $asString
     *
     * @return array|null|string
     */
    public function getTimeTillFormatted(bool $asString = false)
    {
        if (!$this->timeTill) {
            return null;
        }

        $res = 59 == $this->timeTill->format('i')
            ? '24:00'
            : $this->timeTill->format('H:i');

        return $asString
            ? $res
            : [
                'hrs' => (int) explode(':', $res)[0],
                'min' => (int) explode(':', $res)[1],
            ];
    }

    public function getAmenity(): ?Amenity
    {
        return $this->amenity;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
        }

        return $this;
    }

    public function getFreePlacesCount(): int
    {
        return $this->freePlacesCount;
    }

    public function emptyFreePlacesCount(): self
    {
        $this->freePlacesCount = 0;

        return $this;
    }

    public function getBookedPlacesCount(): int
    {
        return $this->bookedPlacesCount;
    }

    public function getDayOfWeek(): string
    {
        return $this->dayOfWeek;
    }

    public function getFirstBooking(): ?Booking
    {
        return $this->bookings->count()
            ? $this->bookings->first()
            : null;
    }

    /** Use only on detached entity?
     * @param string $id
     *
     * @return AmenityTimeSlot
     */
    public function filterBookings(string $id): self
    {
        if ($this->bookings->count()) {
            $this->bookings = $this->bookings->matching(
                Criteria::create()->where(Criteria::expr()->eq('id', $id))
            );
        }

        return $this;
    }

    /**
     * @return iterable|Booking[]
     */
    public function getBookings(): iterable
    {
        return $this->bookings;
    }

    public function getIsBookedByAdministration(): bool
    {
        return $this->isBookedByAdministration;
    }

    public function getIsOld(): bool
    {
        return $this->isOld;
    }

    public function markAsOld(): self
    {
        $this->isOld = true;

        return $this;
    }

    public function bookedTimeslotsCount(): ?int
    {
        return $this->bookedTimeslotsCount;
    }
}
