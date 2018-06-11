<?php

namespace App\Domain\Invoice\Exception;

use Throwable;

class InvalidPaymentAmountException extends \DomainException
{
    public function __construct(
        $message = 'Invalid payment amount.',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
