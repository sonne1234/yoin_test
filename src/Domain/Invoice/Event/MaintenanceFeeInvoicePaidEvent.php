<?php

namespace App\Domain\Invoice\Event;

use App\Domain\Invoice\MaintenanceFeeInvoice;
use App\Domain\NotificationGateway\Message;

class MaintenanceFeeInvoicePaidEvent extends AbstractMaintenanceFeeInvoiceEvent
{
    protected function buildMessage(MaintenanceFeeInvoice $invoice): Message
    {
        return new Message(Message::MAINTENANCE_FEE_PAID, [$invoice->getPayPeriod()->format('Y-m')]);
    }
}
