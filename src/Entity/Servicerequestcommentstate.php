<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Servicerequestcommentstate
 *
 * @ORM\Table(name="servicerequestcommentstate", uniqueConstraints={@ORM\UniqueConstraint(name="user_state", columns={"user_id", "servicerequestcomment_id"}), @ORM\UniqueConstraint(name="uniq_8b75550ebf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_8b75550e917e4e26", columns={"servicerequestcomment_id"}), @ORM\Index(name="idx_8b75550ea76ed395", columns={"user_id"})})
 * @ORM\Entity
 */
class Servicerequestcommentstate
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="servicerequestcommentstate_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="date", nullable=false)
     */
    private $createdat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedat", type="date", nullable=false)
     */
    private $updatedat;

    /**
     * @var bool
     *
     * @ORM\Column(name="isviewed", type="boolean", nullable=false)
     */
    private $isviewed;

    /**
     * @var \Servicerequestcomment
     *
     * @ORM\ManyToOne(targetEntity="Servicerequestcomment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="servicerequestcomment_id", referencedColumnName="id")
     * })
     */
    private $servicerequestcomment;

    /**
     * @var \Useridentity
     *
     * @ORM\ManyToOne(targetEntity="Useridentity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

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

    public function setUpdatedat(\DateTimeInterface $updatedat): self
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    public function getIsviewed(): ?bool
    {
        return $this->isviewed;
    }

    public function setIsviewed(bool $isviewed): self
    {
        $this->isviewed = $isviewed;

        return $this;
    }

    public function getServicerequestcomment(): ?Servicerequestcomment
    {
        return $this->servicerequestcomment;
    }

    public function setServicerequestcomment(?Servicerequestcomment $servicerequestcomment): self
    {
        $this->servicerequestcomment = $servicerequestcomment;

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


}
