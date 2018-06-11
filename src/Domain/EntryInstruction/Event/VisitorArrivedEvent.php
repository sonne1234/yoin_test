<?php

namespace App\Domain\EntryInstruction\Event;

use App\Domain\EntryInstruction\EntryInstruction;
use App\Domain\NotificationGateway\Message;

class VisitorArrivedEvent extends AbstractEntryInstructionEvent
{
    protected function buildMessage(EntryInstruction $entryInstruction)
    {
        return new Message(Message::VISITOR_ARRIVED, [$entryInstruction->getVisitorFirstName(), $entryInstruction->getVisitorLastName()]);
    }
}
