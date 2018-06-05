<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Supportticketstate
 *
 * @ORM\Table(name="supportticketstate", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_97899b61bf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_97899b61a76ed395", columns={"user_id"}), @ORM\Index(name="idx_97899b6168870b2e", columns={"supportticket_id"})})
 * @ORM\Entity
 */
class Supportticketstate
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="supportticketstate_id_seq", allocationSize=1, initialValue=1)
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


}
