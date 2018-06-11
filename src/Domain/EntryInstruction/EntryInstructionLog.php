<?php

namespace App\Domain\EntryInstruction;

use App\Domain\DomainEventPublisher;
use App\Domain\EntryInstruction\Event\VisitorArrivedEvent;
use App\Domain\User\UserIdentity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class EntryInstructionLog
{
    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var EntryInstruction
     * @ORM\ManyToOne(targetEntity="App\Domain\EntryInstruction\EntryInstruction", inversedBy="logs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $entryInstruction;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $arriveAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $exitAt;

    /**
     * @var UserIdentity
     * @ORM\ManyToOne(targetEntity="App\Domain\User\UserIdentity")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $createdBy;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $createdAt;

    public function __construct(
        UserIdentity $createdBy,
        EntryInstruction $entryInstruction,
        ?\DateTime $arriveAt,
        ?\DateTime $exitAt
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->entryInstruction = $entryInstruction;
        $this->createdBy = $createdBy;
        $this->arriveAt = $arriveAt;
        $this->exitAt = $exitAt;
        $this->createdAt = new \DateTime();

        if ($arriveAt) {
            DomainEventPublisher::instance()->publish(
                new VisitorArrivedEvent($this->entryInstruction)
            );
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getArriveAt(): ?\DateTime
    {
        return $this->arriveAt ? clone $this->arriveAt : null;
    }

    /**
     * @return \DateTime
     */
    public function getExitAt(): ?\DateTime
    {
        return $this->exitAt ? clone $this->exitAt : null;
    }

    /**
     * @return UserIdentity
     */
    public function getCreatedBy(): UserIdentity
    {
        return $this->createdBy;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return clone $this->createdAt;
    }

    public function updateExitTime($date): self
    {
        $this->exitAt = $date;

        return $this;
    }
}
