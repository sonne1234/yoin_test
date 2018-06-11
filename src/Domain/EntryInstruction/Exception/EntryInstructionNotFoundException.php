<?php

namespace App\Domain\EntryInstruction\Exception;

use Throwable;

class EntryInstructionNotFoundException extends \DomainException
{
    public function __construct($message = 'Entry instruction is not found.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
