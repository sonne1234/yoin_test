<?php

namespace App\Domain\SupportTicket;

use App\Domain\User\UserIdentity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="user_support_state", columns={"user_id", "supportticketcomment_id"})
 *  })
 */
class SupportTicketCommentState
{
    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var SupportTicketComment
     * @ORM\ManyToOne(targetEntity="App\Domain\SupportTicket\SupportTicketComment", inversedBy="states")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $supportTicketComment;

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

    public function __construct(
        UserIdentity $owner,
        SupportTicketComment $supportTicketComment
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = $this->updatedAt = new \DateTime();

        $this->owner = $owner;
        $this->supportTicketComment = $supportTicketComment;
        $this->isViewed = false;
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

    /**
     * @return UserIdentity
     */
    public function getOwner(): UserIdentity
    {
        return $this->owner;
    }
}
