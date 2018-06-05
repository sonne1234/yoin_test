<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Residentcustomfieldvalue
 *
 * @ORM\Table(name="residentcustomfieldvalue", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_4887ddf9bf396750", columns={"id"}), @ORM\UniqueConstraint(name="uniq_4887ddf937268f008012c5b0", columns={"customfield_id", "resident_id"})}, indexes={@ORM\Index(name="idx_4887ddf98012c5b0", columns={"resident_id"}), @ORM\Index(name="idx_4887ddf9ce50ed56", columns={"customfield_id"})})
 * @ORM\Entity
 */
class Residentcustomfieldvalue
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="residentcustomfieldvalue_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=false)
     */
    private $value;

    /**
     * @var \Useridentity
     *
     * @ORM\ManyToOne(targetEntity="Useridentity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resident_id", referencedColumnName="id")
     * })
     */
    private $resident;

    /**
     * @var \Residentcustomfield
     *
     * @ORM\ManyToOne(targetEntity="Residentcustomfield")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customfield_id", referencedColumnName="id")
     * })
     */
    private $customfield;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getResident(): ?Useridentity
    {
        return $this->resident;
    }

    public function setResident(?Useridentity $resident): self
    {
        $this->resident = $resident;

        return $this;
    }

    public function getCustomfield(): ?Residentcustomfield
    {
        return $this->customfield;
    }

    public function setCustomfield(?Residentcustomfield $customfield): self
    {
        $this->customfield = $customfield;

        return $this;
    }


}
