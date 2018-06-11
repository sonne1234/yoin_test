<?php

namespace App\Domain\EntryInstruction\Event;

use App\Domain\EntryInstruction\EntryInstruction;
use App\Domain\NotificationGateway\Message;

class EntryInstructionCancelledEvent extends AbstractEntryInstructionEvent
{
    protected function buildMessage(EntryInstruction $entryInstruction)
    {
        return new Message(Message::ENTRY_INSTRUCTION_CANCELED, [$entryInstruction->getVisitorFirstName(), $entryInstruction->getVisitorLastName()]);
    }
}
