<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Servicerequeststate
 *
 * @ORM\Table(name="servicerequeststate", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_ec4293a0bf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_ec4293a0a76ed395", columns={"user_id"}), @ORM\Index(name="idx_ec4293a0182e2022", columns={"servicerequest_id"})})
 * @ORM\Entity
 */
class Servicerequeststate
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="servicerequeststate_id_seq", allocationSize=1, initialValue=1)
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
     * @var bool
     *
     * @ORM\Column(name="ispinned", type="boolean", nullable=false)
     */
    private $ispinned;

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

    public function getIspinned(): ?bool
    {
        return $this->ispinned;
    }

    public function setIspinned(bool $ispinned): self
    {
        $this->ispinned = $ispinned;

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


}
