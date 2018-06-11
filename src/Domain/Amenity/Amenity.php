<?php

namespace App\Domain\Amenity;

use App\Domain\Amenity\Event\AmenityCreatedEvent;
use App\Domain\Amenity\Event\AmenitySensitiveBookingDataChangedEvent;
use App\Domain\Amenity\Exception\AmenityNumberOfUsersExceedException;
use App\Domain\Common\Image;
use App\Domain\Condo\Condo;
use App\Domain\Condo\CondoBuilding;
use App\Domain\Condo\Transformer\CondoBuildingTransformer;
use App\Domain\DomainEventPublisher;
use App\Domain\GetEntityByIdInCollectionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class Amenity
{
    use GetEntityByIdInCollectionTrait;

    const MAX_LIMIT_FUTURE_BOOKING_MONTHS = 12;

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
    private $createdAt;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isObservable;

    /**
     * @var AmenityGeneralData|null
     * @ORM\Embedded(class="AmenityGeneralData")
     */
    private $generalData;

    /**
     * @var AmenityAvailabilityData|null
     * @ORM\Embedded(class="AmenityAvailabilityData")
     */
    private $availabilityData;

    /**
     * @var AmenityBookingData|null
     * @ORM\Embedded(class="AmenityBookingData")
     */
    private $bookingData;

    /**
     * @var Condo|null
     * @ORM\ManyToOne(targetEntity="App\Domain\Condo\Condo", inversedBy="amenities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $condo;

    /**
     * @var ArrayCollection Image[]
     * @ORM\ManyToMany(targetEntity="App\Domain\Common\Image")
     * @ORM\OrderBy({"updatedAt" = "DESC"})
     */
    private $images;

    /**
     * @var CondoBuilding|null
     * @ORM\ManyToOne(targetEntity="App\Domain\Condo\CondoBuilding")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $whoCanBook;

    /**
     * @var ArrayCollection AmenityTimeSlot[]
     * @ORM\OneToMany(
     *     targetEntity="AmenityTimeSlot",
     *     mappedBy="amenity",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *   )
     */
    private $timeSlots;

    public function __construct(bool $isObservable)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new \DateTime();
        $this->isObservable = $isObservable;
        $this->images = new ArrayCollection();
        $this->timeSlots = new ArrayCollection();

        DomainEventPublisher::instance()->publish(
            new AmenityCreatedEvent($this)
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setGeneralData(AmenityGeneralData $data, array $images): self
    {
        /** @var Image $image */
        foreach ($this->images as $image) {
            $this->images->removeElement($image->setIsUsed(false));
        }
        /** @var Image $image */
        foreach ($images as $image) {
            $this->images->add($image->setIsUsed(true));
        }

        $this->generalData = $data;

        $this->verifyAmenityDataIsCorrect();

        return $this;
    }

    public function setAvailabilityData(
        AmenityAvailabilityData $data,
        bool $isCheckSensitiveBookingDataIsChanged
    ): self {
        $this->availabilityData = $data;

        $this->verifyAmenityDataIsCorrect();

        if ($isCheckSensitiveBookingDataIsChanged) {
            DomainEventPublisher::instance()->publish(
                new AmenitySensitiveBookingDataChangedEvent($this)
            );
        }

        return $this;
    }

    public function setBookingData(
        AmenityBookingData $data,
        ?CondoBuilding $whoCanBook,
        bool $isCheckSensitiveBookingDataIsChanged
    ): self {
        $oldData = $this->bookingData;

        $this->whoCanBook = $whoCanBook;
        $this->bookingData = $data;

        $this->verifyAmenityDataIsCorrect();

        if ($isCheckSensitiveBookingDataIsChanged
            && $oldData
            && (
                $oldData->getBookingTimeOption() !== $this->bookingData->getBookingTimeOption() ||
                $oldData->getTimeslotDuration() !== $this->bookingData->getTimeslotDuration()
            )
        ) {
            DomainEventPublisher::instance()->publish(
                new AmenitySensitiveBookingDataChangedEvent($this)
            );
        }

        return $this;
    }

    public function setCondo(Condo $condo): self
    {
        $this->condo = $condo;

        return $this;
    }

    public function getCondo(): ?Condo
    {
        return $this->condo;
    }

    public function getName(): string
    {
        return $this->generalData
            ? $this->generalData->getName()
            : '';
    }

    public function getNameLowerCase(): string
    {
        return $this->generalData
            ? $this->generalData->getNameLowerCase()
            : '';
    }

    public function getGeneralData(): ?array
    {
        return $this->generalData
            ? $this->generalData->toArray() + [
                'images' => array_values(array_map(
                    function ($image) {
                        /* @var Image $image */
                        return $image->toArray();
                    },
                    $this->images->toArray()
                )),
            ]
            : null;
    }

    public function getAvailabilityData(): ?array
    {
        return $this->availabilityData
            ? $this->availabilityData->toArray()
            : null;
    }

    public function getAvailabilityDataDaysProcessed(): array
    {
        return $this->availabilityData
            ? $this->availabilityData->getDaysProcessed()
            : [];
    }

    public function getBookingData(): ?array
    {
        return $this->bookingData
            ? $this->bookingData->toArray() + [
                'whoCanBook' => $this->whoCanBook instanceof CondoBuilding
                    ? (new CondoBuildingTransformer())->transform($this->whoCanBook)
                    : null,
            ]
            : null;
    }

    public function getIsObservable(): bool
    {
        return $this->isObservable;
    }

    public function getWhoCanBook(): ?CondoBuilding
    {
        return $this->whoCanBook;
    }

    public function addTimeSlot(AmenityTimeSlot $timeSlot): self
    {
        if (!$this->timeSlots->contains($timeSlot)) {
            $this->timeSlots->add($timeSlot->setAmenity($this));
        }

        return $this;
    }

    public function removeTimeSlot(AmenityTimeSlot $timeSlot): self
    {
        $this->timeSlots->removeElement($timeSlot);

        return $this;
    }

    public function getIsBookingForWholeDay(): bool
    {
        return $this->bookingData && $this->bookingData->getIsBookingForWholeDay();
    }

    public function getLimitFutureBookingMonths(): int
    {
        return $this->bookingData
            ? $this->bookingData->getLimitFutureBookingMonths()
            : 0;
    }

    public function getLimitFutureBookingDate(
        \DateTime $date = null,
        bool $isUseMaxLimitFutureBookingMonthsConst = false
    ): \DateTime {
        $currentDate = $date
            ? clone $date
            : (new \DateTime(null, new \DateTimeZone(AmenityTimeSlot::TIMEZONE)))
                ->setTime(0, 0, 0);

        if ($isUseMaxLimitFutureBookingMonthsConst) {
            $currentDate->modify('+ '.self::MAX_LIMIT_FUTURE_BOOKING_MONTHS.' month');
        } else {
            $currentDate->modify("+{$this->getLimitFutureBookingMonths()} month");
        }

        return $currentDate->modify('-1 day');
    }

    public function getCountOfTimeslotsForOneBooking(): int
    {
        return $this->bookingData
            ? $this->bookingData->getCountOfTimeslotsForOneBooking()
            : 0;
    }

    public function getTimeslotDuration(): int
    {
        return $this->bookingData
            ? $this->bookingData->getTimeslotDuration()
            : 0;
    }

    public function getIsAmenityPaid(): bool
    {
        return $this->bookingData
            ? $this->bookingData->getIsAmenityPaid()
            : false;
    }

    public function getIsBookingRequiresApproval(): bool
    {
        return $this->bookingData
            ? $this->bookingData->getIsBookingRequiresApproval()
            : false;
    }

    public function getCapacity(): int
    {
        return $this->generalData
            ? $this->generalData->getCapacity()
            : 0;
    }

    /**
     * @param \DateTime $date
     *                        TODO  USED ONLY IN TESTS AND FIXTURES  - REFACTOR AND REMOVE THIS
     *
     * @return AmenityTimeSlot[]
     */
    public function getTimeSlotsForDate(\DateTime $date): iterable
    {
        return $this->timeSlots->matching(
            Criteria::create()
                ->where(Criteria::expr()->andX(
//                    Criteria::expr()->gte('type', AmenityTimeSlot::TYPE_TIME_ON_DEMAND),
                    Criteria::expr()->gte('date', (clone $date)->setTime(0, 0, 0)),
                    Criteria::expr()->lte('date', (clone $date)->setTime(23, 59, 59))
                ))
                ->orderBy(['date' => 'ASC', 'timeFrom' => 'ASC'])
        );
    }

    public function getTheLatestTimeSlot(): ?AmenityTimeSlot
    {
        $res = $this->timeSlots->matching(
            Criteria::create()
                ->where(Criteria::expr()->eq(
                    'type',
                    $this->getIsBookingForWholeDay()
                        ? AmenityTimeSlot::TYPE_WHOLE_DAY
                        : AmenityTimeSlot::TYPE_TIME_ON_DEMAND
                ))
                ->andWhere(Criteria::expr()->eq('isOld', false))
                ->orderBy(['date' => 'desc'])
                ->setMaxResults(1)
        );

        return $res->count()
            ? $res->first()
            : null;
    }

    /**
     * @return iterable|AmenityTimeSlot[]
     */
    public function getTimeSlots(): iterable
    {
        return $this->timeSlots;
    }

    public function getIsParallelBookingAllowed(): bool
    {
        return $this->bookingData && $this->bookingData->getIsParallelBookingAllowed();
    }

    public function getLimitBookingPerTimeslot(): int
    {
        return $this->bookingData
            ? $this->bookingData->getLimitBookingPerTimeslot()
            : 0;
    }

    public function getLimitUsersPerBooking(): int
    {
        return $this->bookingData
            ? $this->bookingData->getLimitUsersPerBooking()
            : 0;
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return iterable|AmenityTimeSlot[]
     */
    public function getTimeSlotsInsidePeriod(
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate
    ): iterable {
        return $this->timeSlots->matching(
            Criteria::create()
                ->andWhere(Criteria::expr()->eq('isOld', false))
                ->andWhere(Criteria::expr()->orX(
                    Criteria::expr()->andX(
                        Criteria::expr()->eq('type', AmenityTimeSlot::TYPE_WHOLE_DAY),
                        Criteria::expr()->gte('date', $startDate->setTime(0, 0, 0)),
                        Criteria::expr()->lte('date', $endDate->setTime(0, 0, 0))
                    ),
                    Criteria::expr()->andX(
                        Criteria::expr()->eq('type', AmenityTimeSlot::TYPE_TIME_ON_DEMAND),
                        Criteria::expr()->orX(
                            Criteria::expr()->andX(
                                Criteria::expr()->lte('timeFrom', $startDate),
                                Criteria::expr()->gte('timeTill', $endDate)
                            ),
                            Criteria::expr()->andX(
                                Criteria::expr()->gte('timeFrom', $startDate),
                                Criteria::expr()->lte('timeTill', $endDate)
                            ),
                            Criteria::expr()->andX(
                                Criteria::expr()->gte('timeFrom', $startDate),
                                Criteria::expr()->lt('timeFrom', $endDate)
                            ),
                            Criteria::expr()->andX(
                                Criteria::expr()->gt('timeTill', $startDate),
                                Criteria::expr()->lte('timeTill', $endDate)
                            )
                        )
                    )
                )
                )
        );
    }

    public function getWorkingHoursForDayOfWeek(string $dayOfWeek): ?\stdClass
    {
        if (!$this->availabilityData) {
            return null;
        }

        $days = $this->availabilityData->getDaysProcessed();

        $index = null;
        if (1 === count($days)) {
            $index = 0;
        } else {
            foreach ($days as $key => $day) {
                if ($day['type'] === $dayOfWeek) {
                    $index = $key;
                    break;
                }
            }
        }

        if (is_null($index)) {
            return null;
        }

        return (object) [
            'start' => [
                $days[$index]['from']->format('H'),
                $days[$index]['from']->format('i'),
            ],
            'end' => [
                $days[$index]['till']->format('H'),
                $days[$index]['till']->format('i'),
            ],
        ];
    }

    private function verifyAmenityDataIsCorrect()
    {
        if (!$this->generalData || !$this->bookingData) {
            return;
        }

        if ($this->bookingData->getIsParallelBookingAllowed()
            && $this->bookingData->getLimitUsersPerBooking() > $this->generalData->getCapacity()
        ) {
            throw new AmenityNumberOfUsersExceedException();
        }
    }
}
