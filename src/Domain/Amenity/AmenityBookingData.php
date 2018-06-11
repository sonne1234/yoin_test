<?php

namespace App\Domain\Amenity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class AmenityBookingData
{
    const BOOKING_TIME_ALL = 'allday';
    const BOOKING_TIME_DEMAND = 'ondemand';

    const PAYMENT_FOR_USER = 'user';
    const PAYMENT_FOR_AMENITY = 'amenity';

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isBookingRequiresApproval;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $limitFutureBookingMonths;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $bookingTimeOption;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $timeslotDuration;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $countOfTimeslotsForOneBooking;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isParallelBookingAllowed;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isAmenityPaid;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $paymentForOption;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $paymentTimeslotFee;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $limitBookingPerTimeslot;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $limitUsersPerBooking;

    public function __construct(
        bool $isBookingRequiresApproval,
        int $limitFutureBookingMonths,
        string $bookingTimeOption,
        int $timeslotDuration,
        int $countOfTimeslotsForOneBooking,
        bool $isParallelBookingAllowed,
        bool $isAmenityPaid,
        string $paymentForOption,
        int $paymentTimeslotFee,
        int $limitBookingPerTimeslot,
        int $limitUsersPerBooking
    ) {
        $this->isBookingRequiresApproval = $isBookingRequiresApproval;
        $this->limitFutureBookingMonths = $limitFutureBookingMonths;
        $this->bookingTimeOption = $bookingTimeOption;
        $this->timeslotDuration = $timeslotDuration;
        $this->countOfTimeslotsForOneBooking = $countOfTimeslotsForOneBooking;
        $this->isParallelBookingAllowed = $isParallelBookingAllowed;
        $this->isAmenityPaid = $isAmenityPaid;
        $this->paymentForOption = $paymentForOption;
        $this->paymentTimeslotFee = $paymentTimeslotFee;
        $this->limitBookingPerTimeslot = $limitBookingPerTimeslot;
        $this->limitUsersPerBooking = $limitUsersPerBooking;
    }

    public function toArray(): array
    {
        return [
            'isBookingRequiresApproval' => $this->isBookingRequiresApproval,
            'limitFutureBookingMonths' => $this->limitFutureBookingMonths,
            'bookingTimeOption' => $this->bookingTimeOption,
            'timeslotDuration' => $this->timeslotDuration,
            'countOfTimeslotsForOneBooking' => $this->countOfTimeslotsForOneBooking,
            'isParallelBookingAllowed' => $this->isParallelBookingAllowed,
            'isAmenityPaid' => $this->isAmenityPaid,
            'paymentForOption' => $this->paymentForOption,
            'paymentTimeslotFee' => $this->paymentTimeslotFee,
            'limitBookingPerTimeslot' => $this->limitBookingPerTimeslot,
            'limitUsersPerBooking' => $this->limitUsersPerBooking,
        ];
    }

    public function getIsParallelBookingAllowed(): bool
    {
        return $this->isParallelBookingAllowed;
    }

    public function getLimitBookingPerTimeslot(): int
    {
        return $this->limitBookingPerTimeslot;
    }

    public function getLimitUsersPerBooking(): int
    {
        return $this->limitUsersPerBooking;
    }

    public function getIsBookingForWholeDay(): bool
    {
        return $this->bookingTimeOption === self::BOOKING_TIME_ALL;
    }

    public function getBookingTimeOption(): string
    {
        return $this->bookingTimeOption;
    }

    public function getTimeslotDuration(): int
    {
        return $this->timeslotDuration;
    }

    public function getLimitFutureBookingMonths(): int
    {
        return $this->limitFutureBookingMonths;
    }

    public function getCountOfTimeslotsForOneBooking(): int
    {
        return $this->countOfTimeslotsForOneBooking;
    }

    public function getIsAmenityPaid(): bool
    {
        return $this->isAmenityPaid;
    }

    public function getIsBookingRequiresApproval(): bool
    {
        return $this->isBookingRequiresApproval;
    }
}
