<?php

namespace App\Domain\ServiceRequest;

use App\Domain\Common\Image;
use App\Domain\DomainEventPublisher;
use App\Domain\Resident\Resident;
use App\Domain\ServiceRequest\Event\ServiceRequestCommentCreatedEvent;
use App\Domain\ServiceRequest\Event\ServiceRequestReplyCreatedEvent;
use App\Domain\User\UserIdentity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class ServiceRequestComment
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
     * @var ServiceRequest
     * @ORM\ManyToOne(targetEntity="App\Domain\ServiceRequest\ServiceRequest", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $serviceRequest;

    /**
     * @ORM\ManyToMany(targetEntity="App\Domain\Common\Image")
     * @ORM\JoinTable(name="servicerequests_comments_images",
     *      joinColumns=
     *     {@ORM\JoinColumn(name="servicerequest_comment_id", referencedColumnName="id", onDelete="SET NULL" )},
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
     * @var ArrayCollection ServiceRequestCommentState[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\ServiceRequest\ServiceRequestCommentState",
     *     mappedBy="serviceRequestComment",
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
        ServiceRequest $serviceRequest,
        string $comment
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->images = new ArrayCollection();
        $this->states = new ArrayCollection();
        $this->createdAt = new \DateTime();

        $this->author = $author;
        $this->serviceRequest = $serviceRequest;
        $this->comment = $comment;

        DomainEventPublisher::instance()->publish(
            new ServiceRequestCommentCreatedEvent($this)
        );
        if (!$author instanceof Resident) {
            DomainEventPublisher::instance()->publish(
                new ServiceRequestReplyCreatedEvent($this)
            );
        }
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
     * @return ServiceRequest
     */
    public function getServiceRequest(): ServiceRequest
    {
        return $this->serviceRequest;
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

    private function getState(UserIdentity $user): ServiceRequestCommentState
    {
        $stateCollection = $this->states->matching(
            Criteria::create()
                ->where(Criteria::expr()->eq('owner', $user))
        );
        if ($stateCollection->isEmpty()) {
            $state = new ServiceRequestCommentState(
                $user,
                $this
            );
            $this->addState($state);
        } else {
            $state = $stateCollection->first();
        }

        return $state;
    }

    private function addState(ServiceRequestCommentState $state): self
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
     * @return ServiceRequestComment
     */
    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }
}
