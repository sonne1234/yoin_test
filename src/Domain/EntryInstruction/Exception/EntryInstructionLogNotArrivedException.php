<?php

namespace App\Domain\EntryInstruction\Exception;

use Throwable;

class EntryInstructionLogNotArrivedException extends \DomainException
{
    public function __construct(
        $message = 'Entry instruction arrival record is not found.',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
