<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Condobuilding
 *
 * @ORM\Table(name="condobuilding", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_71bac334c6416e3a", columns={"residentstopic_id"}), @ORM\UniqueConstraint(name="uniq_71bac334bf396750", columns={"id"}), @ORM\UniqueConstraint(name="uniq_71bac3342625223d", columns={"primeresidentstopic_id"})}, indexes={@ORM\Index(name="idx_71bac334e2b100ed", columns={"condo_id"})})
 * @ORM\Entity
 */
class Condobuilding
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="condobuilding_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=255, nullable=false)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=255, nullable=false)
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="fullnamelowercase", type="string", length=255, nullable=false)
     */
    private $fullnamelowercase;

    /**
     * @var \Topic
     *
     * @ORM\ManyToOne(targetEntity="Topic")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="primeresidentstopic_id", referencedColumnName="id")
     * })
     */
    private $primeresidentstopic;

    /**
     * @var \Topic
     *
     * @ORM\ManyToOne(targetEntity="Topic")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="residentstopic_id", referencedColumnName="id")
     * })
     */
    private $residentstopic;

    /**
     * @var \Condo
     *
     * @ORM\ManyToOne(targetEntity="Condo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="condo_id", referencedColumnName="id")
     * })
     */
    private $condo;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Announcement", inversedBy="condobuilding")
     * @ORM\JoinTable(name="condobuilding_announcement",
     *   joinColumns={
     *     @ORM\JoinColumn(name="condobuilding_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="announcement_id", referencedColumnName="id")
     *   }
     * )
     */
    private $announcement;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->announcement = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getFullnamelowercase(): ?string
    {
        return $this->fullnamelowercase;
    }

    public function setFullnamelowercase(string $fullnamelowercase): self
    {
        $this->fullnamelowercase = $fullnamelowercase;

        return $this;
    }

    public function getPrimeresidentstopic(): ?Topic
    {
        return $this->primeresidentstopic;
    }

    public function setPrimeresidentstopic(?Topic $primeresidentstopic): self
    {
        $this->primeresidentstopic = $primeresidentstopic;

        return $this;
    }

    public function getResidentstopic(): ?Topic
    {
        return $this->residentstopic;
    }

    public function setResidentstopic(?Topic $residentstopic): self
    {
        $this->residentstopic = $residentstopic;

        return $this;
    }

    public function getCondo(): ?Condo
    {
        return $this->condo;
    }

    public function setCondo(?Condo $condo): self
    {
        $this->condo = $condo;

        return $this;
    }

    /**
     * @return Collection|Announcement[]
     */
    public function getAnnouncement(): Collection
    {
        return $this->announcement;
    }

    public function addAnnouncement(Announcement $announcement): self
    {
        if (!$this->announcement->contains($announcement)) {
            $this->announcement[] = $announcement;
        }

        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): self
    {
        if ($this->announcement->contains($announcement)) {
            $this->announcement->removeElement($announcement);
        }

        return $this;
    }

}
