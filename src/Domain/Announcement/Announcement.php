<?php

namespace App\Domain\Announcement;

use App\Domain\Common\Image;
use App\Domain\Condo\Condo;
use App\Domain\Condo\CondoBuilding;
use App\Domain\GetEntityByIdInCollectionTrait;
use App\Domain\ImageRemoverEventTrait;
use App\Domain\Resident\Resident;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Announcement
{
    private const STATUS_ORDINARY = 'ordinary';
    private const STATUS_IMPORTANT = 'important';
    const STATUSES = [
        self::STATUS_ORDINARY,
        self::STATUS_IMPORTANT,
    ];

    use
        GetEntityByIdInCollectionTrait,
        ImageRemoverEventTrait;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @var Condo|null
     * @ORM\ManyToOne(targetEntity="App\Domain\Condo\Condo", inversedBy="announcements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $condo;

    /**
     * @var Image[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Domain\Common\Image")
     */
    private $images;

    /**
     * @var CondoBuilding[]|ArrayCollection
     * @ORM\ManyToMany(
     *     targetEntity="App\Domain\Condo\CondoBuilding",
     *     mappedBy="announcements",
     *     cascade={"persist"}
     *    )
     */
    private $condoBuildings;

    /**
     * @var ArrayCollection AnnouncementReadBy[]
     *
     * @ORM\OneToMany(
     *     targetEntity="AnnouncementReadBy",
     *     mappedBy="announcement",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *   )
     */
    private $announcementsReadBy;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new \DateTime();
        $this->images = new ArrayCollection();
        $this->condoBuildings = new ArrayCollection();
        $this->announcementsReadBy = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string                $title
     * @param string                $description
     * @param string                $status
     * @param array|CondoBuilding[] $condoBuildings
     * @param array|Image[]         $images
     * @param bool                  $isUpdating
     *
     * @return $this
     */
    public function updateInfo(
        string $title,
        string $description,
        string $status,
        array $condoBuildings,
        array $images,
        bool $isUpdating = true
    ): self {
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;

        $this->images->clear();
        foreach ($images as $image) {
            $this->images->add($image);
        }

        foreach ($this->condoBuildings as $building) {
            $this->condoBuildings->removeElement($building->removeAnnouncement($this));
        }
        foreach ($condoBuildings as $building) {
            $this->condoBuildings->add($building->addAnnouncement($this));
        }

        if ($isUpdating) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return clone $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt
            ? clone $this->updatedAt
            : null;
    }

    public function setCondo(?Condo $condo): self
    {
        $this->condo = $condo;

        return $this;
    }

    public function getCondo(): ?Condo
    {
        return $this->condo;
    }

    public function getImages(): iterable
    {
        return $this->images;
    }

    public function setIsRead(Resident $resident): self
    {
        if (!$this->getIsRead($resident)) {
            $this->announcementsReadBy->add(
                new AnnouncementReadBy($this, $resident)
            );
        }

        return $this;
    }

    /**
     * @return iterable|CondoBuilding[]
     */
    public function getCondoBuildings(): iterable
    {
        return $this->condoBuildings;
    }

    public function getIsRead(Resident $resident): bool
    {
        return (bool) $this
            ->announcementsReadBy
            ->matching(
                Criteria::create()
                    ->andWhere(Criteria::expr()->eq('resident', $resident))
                    ->andWhere(Criteria::expr()->eq('announcement', $this))
            )
            ->count();
    }

    public function markAsUnreadForAll(): self
    {
        $this->announcementsReadBy->clear();

        return $this;
    }
}
