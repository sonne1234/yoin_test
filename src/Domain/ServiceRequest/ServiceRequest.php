<?php

namespace App\Domain\ServiceRequest;

use App\Domain\Common\Image;
use App\Domain\Condo\Condo;
use App\Domain\DomainEventPublisher;
use App\Domain\Resident\Resident;
use App\Domain\ServiceRequest\Event\ServiceRequestCreatedEvent;
use App\Domain\User\UserIdentity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class ServiceRequest
{
    const SERVICEREQUEST_CATEGORY_UTILITIES = 1;
    const SERVICEREQUEST_CATEGORY_UNIT = 2;
    const SERVICEREQUEST_CATEGORY_BUILDING = 3;
    const SERVICEREQUEST_CATEGORY_OUTDOOR = 4;
    const SERVICEREQUEST_CATEGORY_AMENITY = 5;
    const SERVICEREQUEST_CATEGORY_OTHER = 6;

    const SERVICEREQUEST_CATEGORIES = [
        self::SERVICEREQUEST_CATEGORY_UTILITIES => [
            'name' => 'Utilities',
            'label' => 'UTILITIES',
        ],
        self::SERVICEREQUEST_CATEGORY_UNIT => [
            'name' => 'Unit',
            'label' => 'UNIT',
        ],
        self::SERVICEREQUEST_CATEGORY_BUILDING => [
            'name' => 'Building',
            'label' => 'BUILDING',
        ],
        self::SERVICEREQUEST_CATEGORY_OUTDOOR => [
            'name' => 'Outdoor',
            'label' => 'OUTDOOR',
        ],
        self::SERVICEREQUEST_CATEGORY_AMENITY => [
            'name' => 'Amenity',
            'label' => 'AMENITY',
        ],
        self::SERVICEREQUEST_CATEGORY_OTHER => [
            'name' => 'Other',
            'label' => 'OTHER',
        ],
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
     * @var string
     * @ORM\Column(type="string", unique=false, nullable=false)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @var Resident
     * @ORM\ManyToOne(targetEntity="App\Domain\Resident\Resident", inversedBy="serviceRequests")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $resident;

    /**
     * @ORM\ManyToMany(targetEntity="App\Domain\Common\Image")
     * @ORM\JoinTable(name="servicerequests_images",
     *      joinColumns={@ORM\JoinColumn(name="servicerequest_id", referencedColumnName="id", onDelete="SET NULL" )},
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
     * @var bool
     */
    private $isPinned;

    /**
     * @var ArrayCollection ServiceRequestComment[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\ServiceRequest\ServiceRequestComment",
     *     mappedBy="serviceRequest",
     *     cascade={"persist"}
     *   )
     */
    private $comments;

    /**
     * @var ArrayCollection ServiceRequestState[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\ServiceRequest\ServiceRequestState",
     *     mappedBy="serviceRequest",
     *     cascade={"persist"}
     *   )
     */
    private $states;

    /**
     * @var bool
     */
    private $hasUnreadComments;

    /**
     * @var bool
     */
    private $isRead;

    public function __construct(
        Resident $resident,
        int $category,
        string $title,
        string $description
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->images = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->states = new ArrayCollection();
        $this->createdAt = $this->updatedAt = new \DateTime();
        $this->isPinned = false;

        $this->resident = $resident;
        $this->category = $category;
        $this->title = $title;
        $this->description = $description;

        DomainEventPublisher::instance()->publish(
            new ServiceRequestCreatedEvent($this)
        );
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image->setIsUsed(true));
        }

        return $this;
    }

    public function addComment(ServiceRequestComment $comment): self
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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return Resident
     */
    public function getResident(): Resident
    {
        return $this->resident;
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
     * @return Collection|ServiceRequestComment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function getCondo(): Condo
    {
        return $this->resident->getUnit()->getCondo();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function hasUnreadComments(): bool
    {
        return (bool) $this->hasUnreadComments;
    }

    public function isRead(): bool
    {
        return (bool) $this->isRead;
    }

    public function isPinned(): bool
    {
        return (bool) $this->isPinned;
    }

    private function getState(UserIdentity $user): ServiceRequestState
    {
        $stateCollection = $this->states->matching(
            Criteria::create()
                ->where(Criteria::expr()->eq('owner', $user))
        );
        if ($stateCollection->isEmpty()) {
            $state = new ServiceRequestState(
                $user,
                $this
            );
            $this->addState($state);
        } else {
            $state = $stateCollection->first();
        }

        return $state;
    }

    private function addState(ServiceRequestState $state): self
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

    public function pin(UserIdentity $user): self
    {
        $this->getState($user)->markPinned();
        $this->setIsPinned(true);

        return $this;
    }

    public function unpin(UserIdentity $user): self
    {
        $this->getState($user)->markUnpinned();
        $this->setIsPinned(false);

        return $this;
    }

    /**
     * @param bool $isPinned
     *
     * @return ServiceRequest
     */
    public function setIsPinned(bool $isPinned): self
    {
        $this->isPinned = $isPinned;

        return $this;
    }

    /**
     * @param bool $hasUnreadComments
     *
     * @return ServiceRequest
     */
    public function setHasUnreadComments(bool $hasUnreadComments): self
    {
        $this->hasUnreadComments = $hasUnreadComments;

        return $this;
    }

    /**
     * @param bool $isRead
     *
     * @return ServiceRequest
     */
    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }
}
