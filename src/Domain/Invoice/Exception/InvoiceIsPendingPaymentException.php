<?php

namespace App\Domain\Invoice\Exception;

use Throwable;

class InvoiceIsPendingPaymentException extends \DomainException
{
    public function __construct(
        $message = 'Invoice is in pending payment state.',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
