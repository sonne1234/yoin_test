<?php

namespace App\Domain\EntryInstruction\Exception;

use Throwable;

class EntryInstructionNotActualException extends \DomainException
{
    public function __construct(
        $message = 'Entry instruction is not active.',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
