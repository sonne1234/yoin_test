<?php

namespace App\Domain\Condo;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 *
 * fields are set to be nullable, until resolved in Doctrine 3 (https://github.com/doctrine/doctrine2/pull/1275)
 */
class CondoMaintenanceData
{

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maintenanceFeeSize;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $nextInvoiceDate;

    public function __construct(
        int $maintenanceFeeSize,
        \DateTime $nextInvoiceDate
    ) {
        $this->maintenanceFeeSize = $maintenanceFeeSize;
        $this->nextInvoiceDate = $nextInvoiceDate;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function getNextInvoiceDate(): ?\DateTime
    {
        return $this->nextInvoiceDate ? clone $this->nextInvoiceDate : null;
    }

    public function getMaintenanceFeeSize(): ?int
    {
        return $this->maintenanceFeeSize;
    }

    public function getPayPeriod(): ?\DateTime
    {
        /**
         * invoice is being issued for the current month
         */
        return $this->getNextInvoiceDate() ? $this->getNextInvoiceDate() : null;
    }
}
