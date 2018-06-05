<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Residentcustomfield
 *
 * @ORM\Table(name="residentcustomfield", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_18cae685bf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_18cae685e2b100ed", columns={"condo_id"})})
 * @ORM\Entity
 */
class Residentcustomfield
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="residentcustomfield_id_seq", allocationSize=1, initialValue=1)
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
     * @ORM\Column(name="namelowercase", type="string", length=255, nullable=false)
     */
    private $namelowercase;

    /**
     * @var bool
     *
     * @ORM\Column(name="isrequired", type="boolean", nullable=false)
     */
    private $isrequired;

    /**
     * @var bool
     *
     * @ORM\Column(name="isobservable", type="boolean", nullable=false)
     */
    private $isobservable;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNamelowercase(): ?string
    {
        return $this->namelowercase;
    }

    public function setNamelowercase(string $namelowercase): self
    {
        $this->namelowercase = $namelowercase;

        return $this;
    }

    public function getIsrequired(): ?bool
    {
        return $this->isrequired;
    }

    public function setIsrequired(bool $isrequired): self
    {
        $this->isrequired = $isrequired;

        return $this;
    }

    public function getIsobservable(): ?bool
    {
        return $this->isobservable;
    }

    public function setIsobservable(bool $isobservable): self
    {
        $this->isobservable = $isobservable;

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
