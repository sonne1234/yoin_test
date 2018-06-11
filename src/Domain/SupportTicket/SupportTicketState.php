<?php

namespace App\Domain\SupportTicket;

use App\Domain\User\UserIdentity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class SupportTicketState
{
    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var SupportTicket
     * @ORM\ManyToOne(targetEntity="App\Domain\SupportTicket\SupportTicket", inversedBy="states")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $supportTicket;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=false)
     */
    private $updatedAt;

    /**
     * @var UserIdentity
     * @ORM\ManyToOne(targetEntity="App\Domain\User\UserIdentity")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isViewed;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isPinned;

    public function __construct(
        UserIdentity $owner,
        SupportTicket $supportTicket
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = $this->updatedAt = new \DateTime();

        $this->owner = $owner;
        $this->supportTicket = $supportTicket;
        $this->isViewed = false;
        $this->isPinned = false;
    }

    public function markViewed(): self
    {
        $this->isViewed = true;

        return $this;
    }

    public function isViewed(): bool
    {
        return $this->isViewed;
    }

    public function getIsViewed(): bool
    {
        return $this->isViewed;
    }

    public function markPinned(): self
    {
        $this->isPinned = true;

        return $this;
    }

    public function markUnpinned(): self
    {
        $this->isPinned = false;

        return $this;
    }

    public function isPinned(): bool
    {
        return $this->isPinned;
    }

    /**
     * @return UserIdentity
     */
    public function getOwner(): UserIdentity
    {
        return $this->owner;
    }
}
