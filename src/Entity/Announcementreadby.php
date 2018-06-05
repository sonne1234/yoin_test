<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Announcementreadby
 *
 * @ORM\Table(name="announcementreadby", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_330b9f9cbf396750", columns={"id"}), @ORM\UniqueConstraint(name="uniq_330b9f9c913aea178012c5b0", columns={"announcement_id", "resident_id"})}, indexes={@ORM\Index(name="idx_330b9f9c8012c5b0", columns={"resident_id"}), @ORM\Index(name="idx_330b9f9c913aea17", columns={"announcement_id"})})
 * @ORM\Entity
 */
class Announcementreadby
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="announcementreadby_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Useridentity
     *
     * @ORM\ManyToOne(targetEntity="Useridentity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resident_id", referencedColumnName="id")
     * })
     */
    private $resident;

    /**
     * @var \Announcement
     *
     * @ORM\ManyToOne(targetEntity="Announcement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="announcement_id", referencedColumnName="id")
     * })
     */
    private $announcement;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getResident(): ?Useridentity
    {
        return $this->resident;
    }

    public function setResident(?Useridentity $resident): self
    {
        $this->resident = $resident;

        return $this;
    }

    public function getAnnouncement(): ?Announcement
    {
        return $this->announcement;
    }

    public function setAnnouncement(?Announcement $announcement): self
    {
        $this->announcement = $announcement;

        return $this;
    }


}
