<?php

namespace App\Domain\Invoice\Exception;

use Throwable;

class InvoiceNotFoundException extends \DomainException
{
    public function __construct($message = 'Invoice is not found.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
