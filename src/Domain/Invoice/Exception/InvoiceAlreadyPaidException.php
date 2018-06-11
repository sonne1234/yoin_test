<?php

namespace App\Domain\Invoice\Exception;

use Throwable;

class InvoiceAlreadyPaidException extends \DomainException
{
    public function __construct(
        $message = 'Invoice has been paid already.',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
