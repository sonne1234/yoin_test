<?php

namespace App\Domain\SupportTicket;

use App\Domain\Common\Image;
use App\Domain\DomainEventPublisher;
use App\Domain\SupportTicket\Event\SupportTicketCommentCreatedEvent;
use App\Domain\User\UserIdentity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class SupportTicketComment
{
    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $comment;

    /**
     * @var SupportTicket
     * @ORM\ManyToOne(targetEntity="App\Domain\SupportTicket\SupportTicket", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $supportTicket;

    /**
     * @ORM\ManyToMany(targetEntity="App\Domain\Common\Image")
     * @ORM\JoinTable(name="supportticket_comments_images",
     *      joinColumns=
     *     {@ORM\JoinColumn(name="supportticket_comment_id", referencedColumnName="id", onDelete="SET NULL" )},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", unique=false)}
     *      )
     */
    private $images;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var UserIdentity
     * @ORM\ManyToOne(targetEntity="App\Domain\User\UserIdentity")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @var ArrayCollection SupportTicketCommentState[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\SupportTicket\SupportTicketCommentState",
     *     mappedBy="supportTicketComment",
     *     cascade={"persist"}
     *   )
     */
    private $states;

    /**
     * @var bool
     */
    private $isRead;

    public function __construct(
        UserIdentity $author,
        SupportTicket $supportTicket,
        string $comment
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->images = new ArrayCollection();
        $this->states = new ArrayCollection();
        $this->createdAt = new \DateTime();

        $this->author = $author;
        $this->supportTicket = $supportTicket;
        $this->comment = $comment;

        DomainEventPublisher::instance()->publish(
            new SupportTicketCommentCreatedEvent(
                $this->supportTicket->getId()
            )
        );
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image->setIsUsed(true));
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @return SupportTicket
     */
    public function getServiceRequest(): SupportTicket
    {
        return $this->supportTicket;
    }

    /**
     * @return SupportTicket
     */
    public function getSupportTicket(): SupportTicket
    {
        return $this->supportTicket;
    }

    /**
     * @return Image[]
     */
    public function getImages()
    {
        return array_values(array_map(
            function ($image) {
                /* @var Image $image */
                return $image->toArray();
            },
            $this->images->toArray()
        ));
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return UserIdentity
     */
    public function getAuthor(): UserIdentity
    {
        return $this->author;
    }

    public function isRead(): bool
    {
        return (bool) $this->isRead;
    }

    public function getState(UserIdentity $user): SupportTicketCommentState
    {
        $stateCollection = $this->states->matching(
            Criteria::create()
                ->where(Criteria::expr()->eq('owner', $user))
        );
        if ($stateCollection->isEmpty()) {
            $state = new SupportTicketCommentState(
                $user,
                $this
            );
            $this->addState($state);
        } else {
            $state = $stateCollection->first();
        }

        return $state;
    }

    private function addState(SupportTicketCommentState $state): self
    {
        if (!$this->states->contains($state)) {
            $this->states->add($state);
        }

        return $this;
    }

    public function markRead(UserIdentity $user): self
    {
        $this->getState($user)->markViewed();
        $this->setIsRead(true);

        return $this;
    }

    /**
     * @param bool $isRead
     *
     * @return SupportTicketComment
     */
    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }
}
