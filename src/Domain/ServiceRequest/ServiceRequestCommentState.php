<?php

namespace App\Domain\ServiceRequest;

use App\Domain\User\UserIdentity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="user_state", columns={"user_id", "servicerequestcomment_id"})
 *  })
 */
class ServiceRequestCommentState
{
    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var ServiceRequestComment
     * @ORM\ManyToOne(targetEntity="App\Domain\ServiceRequest\ServiceRequestComment", inversedBy="states")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $serviceRequestComment;

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
        ServiceRequestComment $serviceRequestComment
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = $this->updatedAt = new \DateTime();

        $this->owner = $owner;
        $this->serviceRequestComment = $serviceRequestComment;
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
