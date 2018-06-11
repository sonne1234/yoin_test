<?php

namespace App\Domain\EntryInstruction\Exception;

use Throwable;

class EntryInstructionLogAlreadyUsedException extends \DomainException
{
    public function __construct(
        $message = 'One time entry instruction was already used.',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
