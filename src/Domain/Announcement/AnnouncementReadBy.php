<?php

namespace App\Domain\Announcement;

use App\Domain\Resident\Resident;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"announcement_id", "resident_id"})
 *  }
 * )
 */
class AnnouncementReadBy
{
    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var Announcement
     * @ORM\ManyToOne(targetEntity="Announcement", inversedBy="announcementsReadBy")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $announcement;

    /**
     * @var Resident
     * @ORM\ManyToOne(targetEntity="App\Domain\Resident\Resident")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $resident;

    public function __construct(Announcement $announcement, Resident $resident)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->announcement = $announcement;
        $this->resident = $resident;
    }

    public function getResident(): Resident
    {
        return $this->resident;
    }

    public function getAnnouncement(): Announcement
    {
        return $this->announcement;
    }
}
