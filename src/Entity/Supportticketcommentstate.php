<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Supportticketcommentstate
 *
 * @ORM\Table(name="supportticketcommentstate", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_2fb7bd0bbf396750", columns={"id"}), @ORM\UniqueConstraint(name="user_support_state", columns={"user_id", "supportticketcomment_id"})}, indexes={@ORM\Index(name="idx_2fb7bd0ba76ed395", columns={"user_id"}), @ORM\Index(name="idx_2fb7bd0b2dbd8cdc", columns={"supportticketcomment_id"})})
 * @ORM\Entity
 */
class Supportticketcommentstate
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="supportticketcommentstate_id_seq", allocationSize=1, initialValue=1)
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
     * @var \Supportticketcomment
     *
     * @ORM\ManyToOne(targetEntity="Supportticketcomment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="supportticketcomment_id", referencedColumnName="id")
     * })
     */
    private $supportticketcomment;

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

    public function getSupportticketcomment(): ?Supportticketcomment
    {
        return $this->supportticketcomment;
    }

    public function setSupportticketcomment(?Supportticketcomment $supportticketcomment): self
    {
        $this->supportticketcomment = $supportticketcomment;

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
