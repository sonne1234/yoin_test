<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Supportticketcomment
 *
 * @ORM\Table(name="supportticketcomment", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_10dafdd2bf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_10dafdd2a76ed395", columns={"user_id"}), @ORM\Index(name="idx_10dafdd268870b2e", columns={"supportticket_id"})})
 * @ORM\Entity
 */
class Supportticketcomment
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="supportticketcomment_id_seq", allocationSize=1, initialValue=1)
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
     * @var \Supportticket
     *
     * @ORM\ManyToOne(targetEntity="Supportticket")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="supportticket_id", referencedColumnName="id")
     * })
     */
    private $supportticket;

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
     * @ORM\ManyToMany(targetEntity="Image", inversedBy="supportticketComment")
     * @ORM\JoinTable(name="supportticket_comments_images",
     *   joinColumns={
     *     @ORM\JoinColumn(name="supportticket_comment_id", referencedColumnName="id")
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

    public function getSupportticket(): ?Supportticket
    {
        return $this->supportticket;
    }

    public function setSupportticket(?Supportticket $supportticket): self
    {
        $this->supportticket = $supportticket;

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
