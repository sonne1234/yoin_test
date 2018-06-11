<?php

namespace App\Domain\Invoice\Event;

use App\Domain\Invoice\MaintenanceFeeInvoice;
use App\Domain\NotificationGateway\Message;

class MaintenanceFeeInvoiceCreatedEvent extends AbstractMaintenanceFeeInvoiceEvent
{
    protected function buildMessage(MaintenanceFeeInvoice $invoice): Message
    {
        return new Message(Message::MAINTENANCE_FEE_PENDING, [$invoice->getPayPeriod()->format('Y-m')]);
    }
}
