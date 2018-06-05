<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Announcement
 *
 * @ORM\Table(name="announcement", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_558802e4bf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_558802e4e2b100ed", columns={"condo_id"})})
 * @ORM\Entity
 */
class Announcement
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="announcement_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetimetz", nullable=false)
     */
    private $createdat;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updatedat", type="datetimetz", nullable=true)
     */
    private $updatedat;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

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
     * @ORM\ManyToMany(targetEntity="Condobuilding", mappedBy="announcement")
     */
    private $condobuilding;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Image", inversedBy="announcement")
     * @ORM\JoinTable(name="announcement_image",
     *   joinColumns={
     *     @ORM\JoinColumn(name="announcement_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     *   }
     * )
     */
    private $image;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->condobuilding = new \Doctrine\Common\Collections\ArrayCollection();
        $this->image = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(\DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getUpdatedat(): ?\DateTimeInterface
    {
        return $this->updatedat;
    }

    public function setUpdatedat(?\DateTimeInterface $updatedat): self
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
     * @return Collection|Condobuilding[]
     */
    public function getCondobuilding(): Collection
    {
        return $this->condobuilding;
    }

    public function addCondobuilding(Condobuilding $condobuilding): self
    {
        if (!$this->condobuilding->contains($condobuilding)) {
            $this->condobuilding[] = $condobuilding;
            $condobuilding->addAnnouncement($this);
        }

        return $this;
    }

    public function removeCondobuilding(Condobuilding $condobuilding): self
    {
        if ($this->condobuilding->contains($condobuilding)) {
            $this->condobuilding->removeElement($condobuilding);
            $condobuilding->removeAnnouncement($this);
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Image $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->image->contains($image)) {
            $this->image->removeElement($image);
        }

        return $this;
    }

}
