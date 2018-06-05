<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Servicerequestcomment
 *
 * @ORM\Table(name="servicerequestcomment", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_cce0e824bf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_cce0e824182e2022", columns={"servicerequest_id"}), @ORM\Index(name="idx_cce0e824a76ed395", columns={"user_id"})})
 * @ORM\Entity
 */
class Servicerequestcomment
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="servicerequestcomment_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=false)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime", nullable=false)
     */
    private $createdat;

    /**
     * @var \Servicerequest
     *
     * @ORM\ManyToOne(targetEntity="Servicerequest")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="servicerequest_id", referencedColumnName="id")
     * })
     */
    private $servicerequest;

    /**
     * @var \Useridentity
     *
     * @ORM\ManyToOne(targetEntity="Useridentity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Image", inversedBy="servicerequestComment")
     * @ORM\JoinTable(name="servicerequests_comments_images",
     *   joinColumns={
     *     @ORM\JoinColumn(name="servicerequest_comment_id", referencedColumnName="id")
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
        $this->image = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
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

    public function getServicerequest(): ?Servicerequest
    {
        return $this->servicerequest;
    }

    public function setServicerequest(?Servicerequest $servicerequest): self
    {
        $this->servicerequest = $servicerequest;

        return $this;
    }

    public function getUser(): ?Useridentity
    {
        return $this->user;
    }

    public function setUser(?Useridentity $user): self
    {
        $this->user = $user;

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
