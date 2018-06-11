<?php

namespace App\Domain\Condo;

use App\Domain\Announcement\Announcement;
use App\Domain\Condo\Event\CondoBuildingCreatedEvent;
use App\Domain\Condo\Exception\CondoBuildingHasUnitsException;
use App\Domain\Condo\Exception\UnitWithTheSameNumberAlreadyExistsInBuildingException;
use App\Domain\NotificationGateway\Topic;
use App\Domain\Unit\Unit;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class CondoBuilding implements ContainsRecordedMessages
{
    use PrivateMessageRecorderCapabilities;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $number;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $fullName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $fullNameLowerCase;

    /**
     * @var Condo|null
     * @ORM\ManyToOne(targetEntity="Condo", inversedBy="buildings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $condo;

    /**
     * @var ArrayCollection Unit[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Unit\Unit",
     *     mappedBy="condoBuilding"
     *   )
     */
    private $units;

    /**
     * @var ArrayCollection Announcement[]
     * @ORM\ManyToMany(
     *     targetEntity="App\Domain\Announcement\Announcement",
     *     inversedBy="condoBuildings"
     *    )
     */
    private $announcements;

    /**
     * @var Topic
     * @ORM\OneToOne(targetEntity="App\Domain\NotificationGateway\Topic", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $residentsTopic;

    /**
     * @var Topic
     * @ORM\OneToOne(targetEntity="App\Domain\NotificationGateway\Topic", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $primeResidentsTopic;

    public function __construct(
        string $name,
        string $number
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->units = new ArrayCollection();
        $this->announcements = new ArrayCollection();

        $this->updateInfo($name, $number);

        $this->record(new CondoBuildingCreatedEvent($this));
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setCondo(Condo $condo): self
    {
        $this->condo = $condo;

        return $this;
    }

    public function updateInfo(string $name, string $number): self
    {
        $this->name = $name;
        $this->number = $number;

        $this->fullName = $this->number.('' !== $this->name ? " ({$this->name})" : '');
        $this->fullNameLowerCase = mb_strtolower($this->fullName);

        return $this;
    }

    public function addUnit(Unit $unit)
    {
        // check if condoBuilding already has unit with the same number
        if ($this
            ->units
            ->matching(Criteria::create()->where(
                Criteria::expr()->eq('numberLowerCase', $unit->getNumberLowerCase())
            ))->count()) {
            throw new UnitWithTheSameNumberAlreadyExistsInBuildingException();
        }

        $this->units->add(
            $unit->setCondoBuilding($this)
        );
    }

    public function removeUnit(Unit $unit)
    {
        $this->units->removeElement(
            $unit->setCondoBuilding(null)
        );
    }

    public function removeAnnouncement(Announcement $announcement): self
    {
        $this->announcements->removeElement($announcement);

        return $this;
    }

    public function addAnnouncement(Announcement $announcement): self
    {
        if (!$this->announcements->contains($announcement)) {
            $this->announcements->add($announcement);
        }

        return $this;
    }

    public function remove()
    {
        if ($this->condo) {
            if (!$this->units->isEmpty()) {
                throw new CondoBuildingHasUnitsException($this);
            }

            $this->condo->getBuildings()->removeElement($this);
            $this->condo = null;
        }
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getCondo(): ?Condo
    {
        return $this->condo;
    }

    /**
     * @return Collection|Unit[]
     */
    public function getUnits(): Collection
    {
        return $this->units->matching(
            Criteria::create()->orderBy(['numberLowerCase' => 'asc'])
        );
    }

    public function getUnitsCount(): int
    {
        return $this->units->count();
    }

    public function getFullNameLowerCase(): string
    {
        return $this->fullNameLowerCase;
    }

    /**
     * @return Topic
     */
    public function getResidentsTopic(): ?Topic
    {
        return $this->residentsTopic;
    }

    /**
     * @param Topic $residentsTopic
     *
     * @return CondoBuilding
     */
    public function setResidentsTopic(Topic $residentsTopic): self
    {
        $this->residentsTopic = $residentsTopic;

        return $this;
    }

    /**
     * @return Topic
     */
    public function getPrimeResidentsTopic(): ?Topic
    {
        return $this->primeResidentsTopic;
    }

    /**
     * @param Topic $primeResidentsTopic
     *
     * @return CondoBuilding
     */
    public function setPrimeResidentsTopic(Topic $primeResidentsTopic): self
    {
        $this->primeResidentsTopic = $primeResidentsTopic;

        return $this;
    }
}
