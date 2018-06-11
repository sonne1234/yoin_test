<?php

namespace App\Domain\EntryInstruction\Event;

use App\Domain\DomainEvent;
use App\Domain\EntryInstruction\EntryInstruction;
use App\Domain\NotificationGateway\Message;
use App\Domain\NotificationGateway\NotificationInterface;

abstract class AbstractEntryInstructionEvent extends DomainEvent implements NotificationInterface
{
    private $entryInstructionId;

    private $residentIds = [];

    /** @var Message */
    private $message;

    /**
     * EntryInstructionCreatedEvent constructor.
     *
     * @param EntryInstruction $entryInstruction
     */
    public function __construct(EntryInstruction $entryInstruction)
    {
        $this->entryInstructionId = $entryInstruction->getId();
        $this->message = $this->buildMessage($entryInstruction);
        if ($entryInstruction->getResident()->getId() !== $entryInstruction->getCreatedBy()->getId()) {
            $this->residentIds[] = $entryInstruction->getResident()->getId();
        }
    }

    public function entryInstructionId()
    {
        return $this->entryInstructionId;
    }

    /**
     * @return string
     */
    public function getMessageRecipientIds(): array
    {
        return $this->residentIds;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    abstract protected function buildMessage(EntryInstruction $entryInstruction);
}
