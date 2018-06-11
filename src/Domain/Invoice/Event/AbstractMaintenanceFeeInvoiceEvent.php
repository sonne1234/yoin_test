<?php

namespace App\Domain\Invoice\Event;

use App\Domain\DomainEvent;
use App\Domain\Invoice\MaintenanceFeeInvoice;
use App\Domain\NotificationGateway\Message;
use App\Domain\NotificationGateway\NotificationInterface;

abstract class AbstractMaintenanceFeeInvoiceEvent extends DomainEvent implements NotificationInterface
{
    /** @var array */
    private $primeResidentIds = [];

    /** @var Message */
    private $message;

    /** @var MaintenanceFeeInvoice */
    protected $invoice;

    public function __construct(
        MaintenanceFeeInvoice $invoice
    ) {
        $this->invoice = $invoice;
        $this->message = $this->buildMessage($invoice);
        if ($invoice->getUnit()->getFirstPrimeUser()) {
            $this->primeResidentIds[] = $invoice->getUnit()->getFirstPrimeUser()->getId();
        }
    }

    /**
     * @return string
     */
    public function getMessageRecipientIds(): array
    {
        return $this->primeResidentIds;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @return MaintenanceFeeInvoice
     */
    public function getInvoice(): MaintenanceFeeInvoice
    {
        return $this->invoice;
    }

    abstract protected function buildMessage(MaintenanceFeeInvoice $invoice): Message;
}
