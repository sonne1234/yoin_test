<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Platformnotification
 *
 * @ORM\Table(name="platformnotification", indexes={@ORM\Index(name="idx_ae4ec2939b6b5fba", columns={"account_id"}), @ORM\Index(name="idx_ae4ec293e2b100ed", columns={"condo_id"})})
 * @ORM\Entity
 */
class Platformnotification
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="guid", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="platformnotification_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="messageargs", type="array", nullable=false)
     */
    private $messageargs;

    /**
     * @var string|null
     *
     * @ORM\Column(name="targetentityid", type="string", length=255, nullable=true)
     */
    private $targetentityid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime", nullable=false)
     */
    private $createdat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedat", type="datetime", nullable=false)
     */
    private $updatedat;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var \Account
     *
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     * })
     */
    private $account;

    /**
     * @var \Condo
     *
     * @ORM\ManyToOne(targetEntity="Condo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="condo_id", referencedColumnName="id")
     * })
     */
    private $condo;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMessageargs(): ?array
    {
        return $this->messageargs;
    }

    public function setMessageargs(array $messageargs): self
    {
        $this->messageargs = $messageargs;

        return $this;
    }

    public function getTargetentityid(): ?string
    {
        return $this->targetentityid;
    }

    public function setTargetentityid(?string $targetentityid): self
    {
        $this->targetentityid = $targetentityid;

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

    public function getUpdatedat(): ?\DateTimeInterface
    {
        return $this->updatedat;
    }

    public function setUpdatedat(\DateTimeInterface $updatedat): self
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

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


}
