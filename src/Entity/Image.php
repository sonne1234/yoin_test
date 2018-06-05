<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="image", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_4fc2b5bbf396750", columns={"id"}), @ORM\UniqueConstraint(name="uniq_4fc2b5baede12f4", columns={"imagenamecropped"}), @ORM\UniqueConstraint(name="uniq_4fc2b5beb901891", columns={"imagename"})})
 * @ORM\Entity
 */
class Image
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="image_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="imagename", type="string", length=255, nullable=false)
     */
    private $imagename;

    /**
     * @var string|null
     *
     * @ORM\Column(name="imagenamecropped", type="string", length=255, nullable=true)
     */
    private $imagenamecropped;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedat", type="datetime", nullable=false)
     */
    private $updatedat;

    /**
     * @var bool
     *
     * @ORM\Column(name="isused", type="boolean", nullable=false)
     */
    private $isused;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Supportticketcomment", mappedBy="image")
     */
    private $supportticketComment;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Servicerequestcomment", mappedBy="image")
     */
    private $servicerequestComment;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Servicerequest", mappedBy="image")
     */
    private $servicerequest;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Announcement", mappedBy="image")
     */
    private $announcement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Amenity", mappedBy="image")
     */
    private $amenity;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Supportticket", mappedBy="image")
     */
    private $supportticket;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->supportticketComment = new \Doctrine\Common\Collections\ArrayCollection();
        $this->servicerequestComment = new \Doctrine\Common\Collections\ArrayCollection();
        $this->servicerequest = new \Doctrine\Common\Collections\ArrayCollection();
        $this->announcement = new \Doctrine\Common\Collections\ArrayCollection();
        $this->amenity = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supportticket = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getImagename(): ?string
    {
        return $this->imagename;
    }

    public function setImagename(string $imagename): self
    {
        $this->imagename = $imagename;

        return $this;
    }

    public function getImagenamecropped(): ?string
    {
        return $this->imagenamecropped;
    }

    public function setImagenamecropped(?string $imagenamecropped): self
    {
        $this->imagenamecropped = $imagenamecropped;

        return $this;
    }

    public function getUpdatedat(): ?\DateTimeInterface
    {
        return $this->updatedat;
    }

    public function setUpdatedat(\DateTimeInterface $updatedat): self
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    public function getIsused(): ?bool
    {
        return $this->isused;
    }

    public function setIsused(bool $isused): self
    {
        $this->isused = $isused;

        return $this;
    }

    /**
     * @return Collection|Supportticketcomment[]
     */
    public function getSupportticketComment(): Collection
    {
        return $this->supportticketComment;
    }

    public function addSupportticketComment(Supportticketcomment $supportticketComment): self
    {
        if (!$this->supportticketComment->contains($supportticketComment)) {
            $this->supportticketComment[] = $supportticketComment;
            $supportticketComment->addImage($this);
        }

        return $this;
    }

    public function removeSupportticketComment(Supportticketcomment $supportticketComment): self
    {
        if ($this->supportticketComment->contains($supportticketComment)) {
            $this->supportticketComment->removeElement($supportticketComment);
            $supportticketComment->removeImage($this);
        }

        return $this;
    }

    /**
     * @return Collection|Servicerequestcomment[]
     */
    public function getServicerequestComment(): Collection
    {
        return $this->servicerequestComment;
    }

    public function addServicerequestComment(Servicerequestcomment $servicerequestComment): self
    {
        if (!$this->servicerequestComment->contains($servicerequestComment)) {
            $this->servicerequestComment[] = $servicerequestComment;
            $servicerequestComment->addImage($this);
        }

        return $this;
    }

    public function removeServicerequestComment(Servicerequestcomment $servicerequestComment): self
    {
        if ($this->servicerequestComment->contains($servicerequestComment)) {
            $this->servicerequestComment->removeElement($servicerequestComment);
            $servicerequestComment->removeImage($this);
        }

        return $this;
    }

    /**
     * @return Collection|Servicerequest[]
     */
    public function getServicerequest(): Collection
    {
        return $this->servicerequest;
    }

    public function addServicerequest(Servicerequest $servicerequest): self
    {
        if (!$this->servicerequest->contains($servicerequest)) {
            $this->servicerequest[] = $servicerequest;
            $servicerequest->addImage($this);
        }

        return $this;
    }

    public function removeServicerequest(Servicerequest $servicerequest): self
    {
        if ($this->servicerequest->contains($servicerequest)) {
            $this->servicerequest->removeElement($servicerequest);
            $servicerequest->removeImage($this);
        }

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
            $announcement->addImage($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): self
    {
        if ($this->announcement->contains($announcement)) {
            $this->announcement->removeElement($announcement);
            $announcement->removeImage($this);
        }

        return $this;
    }

    /**
     * @return Collection|Amenity[]
     */
    public function getAmenity(): Collection
    {
        return $this->amenity;
    }

    public function addAmenity(Amenity $amenity): self
    {
        if (!$this->amenity->contains($amenity)) {
            $this->amenity[] = $amenity;
            $amenity->addImage($this);
        }

        return $this;
    }

    public function removeAmenity(Amenity $amenity): self
    {
        if ($this->amenity->contains($amenity)) {
            $this->amenity->removeElement($amenity);
            $amenity->removeImage($this);
        }

        return $this;
    }

    /**
     * @return Collection|Supportticket[]
     */
    public function getSupportticket(): Collection
    {
        return $this->supportticket;
    }

    public function addSupportticket(Supportticket $supportticket): self
    {
        if (!$this->supportticket->contains($supportticket)) {
            $this->supportticket[] = $supportticket;
            $supportticket->addImage($this);
        }

        return $this;
    }

    public function removeSupportticket(Supportticket $supportticket): self
    {
        if ($this->supportticket->contains($supportticket)) {
            $this->supportticket->removeElement($supportticket);
            $supportticket->removeImage($this);
        }

        return $this;
    }

}
