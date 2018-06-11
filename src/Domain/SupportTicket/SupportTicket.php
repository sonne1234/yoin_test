<?php

namespace App\Domain\SupportTicket;

use App\Domain\Account\Account;
use App\Domain\Common\Image;
use App\Domain\Condo\Condo;
use App\Domain\DomainEventPublisher;
use App\Domain\SupportTicket\Event\SupportTicketCreatedEvent;
use App\Domain\User\UserIdentity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class SupportTicket
{
    const LEVEL_RESIDENT = 1;
    const LEVEL_CONDO = 2;
    const LEVEL_ACCOUNT = 3;

    const LEVELS = [
        self::LEVEL_RESIDENT => 'resident',
        self::LEVEL_CONDO => 'condo',
        self::LEVEL_ACCOUNT => 'account',
    ];

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    private $category;

    /**
     * @var int
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    private $subCategory;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @var UserIdentity
     * @ORM\ManyToOne(targetEntity="App\Domain\User\UserIdentity")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $createdBy;

    /**
     * @var Condo
     * @ORM\ManyToOne(targetEntity="App\Domain\Condo\Condo")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $condo;

    /**
     * @var Account
     * @ORM\ManyToOne(targetEntity="App\Domain\Account\Account")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $account;

    /**
     * @var ArrayCollection Image[]
     * @ORM\ManyToMany(targetEntity="App\Domain\Common\Image")
     * @ORM\JoinTable(name="supportticket_images",
     *      joinColumns={@ORM\JoinColumn(name="supportticket_id", referencedColumnName="id", onDelete="SET NULL" )},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $images;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var ArrayCollection SupportTicketComment[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\SupportTicket\SupportTicketComment",
     *     mappedBy="supportTicket",
     *     cascade={"persist"}
     *   )
     */
    private $comments;

    /**
     * @var ArrayCollection SupportTicketState[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\SupportTicket\SupportTicketState",
     *     mappedBy="supportTicket",
     *     cascade={"persist"}
     *   )
     */
    private $states;

    /**
     * @var int
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    private $level;

    /**
     * @var bool
     */
    private $isRead;

    /**
     * @var bool
     */
    private $hasUreadComments;

    /** @var bool */
    private $isPinned;

    public function __construct(
        UserIdentity $createdBy,
        int $category,
        int $subCategory,
        string $description,
        int $level = self::LEVEL_RESIDENT,
        Condo $condo = null,
        Account $account = null
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->images = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->states = new ArrayCollection();
        $this->level = $level;
        $this->createdAt = $this->updatedAt = new \DateTime();
        $this->isPinned = false;

        $this->createdBy = $createdBy;
        $this->category = $category;
        $this->subCategory = $subCategory;
        $this->description = $description;
        $this->condo = $condo;
        $this->account = $account;

        DomainEventPublisher::instance()->publish(
            new SupportTicketCreatedEvent($this)
        );
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image->setIsUsed(true));
        }

        return $this;
    }

    public function addComment(SupportTicketComment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getCategory(): int
    {
        return $this->category;
    }

    /**
     * @return int
     */
    public function getSubCategory(): ?int
    {
        return $this->subCategory;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return UserIdentity
     */
    public function getUser(): UserIdentity
    {
        return $this->createdBy;
    }

    /**
     * @return Image[]
     */
    public function getImages(): array
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
        return clone $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return clone $this->updatedAt;
    }

    /**
     * @return Collection|SupportTicketComment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function getCondo(): ?Condo
    {
        return $this->condo;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function setUnreadComments(UserIdentity $user = null): self
    {
        if (!$user) {
            $user = $this->createdBy;
        }

        $this->hasUreadComments = (bool) count(array_filter(
            $this->comments->toArray(),
            function (SupportTicketComment $comment) use ($user) {
                return !$comment->getState($user)->isViewed();
            })
        );

        return $this;
    }

    public function hasUnreadComments(): bool
    {
        return (bool) $this->hasUreadComments;
    }

    public function isRead(): bool
    {
        return (bool) $this->isRead;
    }

    private function getState(UserIdentity $user): SupportTicketState
    {
        $stateCollection = $this->states->matching(
            Criteria::create()
                ->where(Criteria::expr()->eq('owner', $user))
        );
        if ($stateCollection->isEmpty()) {
            $state = new SupportTicketState(
                $user,
                $this
            );
            $this->addState($state);
        } else {
            $state = $stateCollection->first();
        }

        return $state;
    }

    private function addState(SupportTicketState $state): self
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
     * @return SupportTicket
     */
    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount(): ?Account
    {
        return $this->account;
    }
}
