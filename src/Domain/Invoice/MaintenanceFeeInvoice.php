<?php

namespace App\Domain\Invoice;

use App\Domain\DomainEventPublisher;
use App\Domain\Invoice\Event\MaintenanceFeeInvoiceCreatedEvent;
use App\Domain\Unit\Unit;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class MaintenanceFeeInvoice extends Invoice
{
    /**
     * @var Unit|null
     * @ORM\ManyToOne(targetEntity="App\Domain\Unit\Unit", inversedBy="invoices")
     */
    private $unit;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=false)
     */
    private $payPeriod;

    public function __construct($amount, Unit $unit, \DateTime $payPeriod)
    {
        parent::__construct($amount);
        $this->setUnit($unit);
        $this->setPayPeriod($payPeriod);
        if ($unit->isAtLeastOnePrimeResidentExists()) {
            DomainEventPublisher::instance()->publish(
                new MaintenanceFeeInvoiceCreatedEvent($this)
            );
        }
    }

    public function setUnit(Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    private function setPayPeriod(\DateTime $payPeriod): self
    {
        $payPeriod = clone $payPeriod;
        $payPeriod->setTime(0, 0, 0);
        $this->payPeriod = $payPeriod;

        return $this;
    }

    public function getPayPeriod(): \DateTime
    {
        return clone $this->payPeriod;
    }

    public function getUnit(): Unit
    {
        return $this->unit;
    }
}
