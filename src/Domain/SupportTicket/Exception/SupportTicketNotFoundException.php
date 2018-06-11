<?php

namespace App\Domain\SupportTicket\Exception;

class SupportTicketNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Support Ticket is not found.');
    }
}
