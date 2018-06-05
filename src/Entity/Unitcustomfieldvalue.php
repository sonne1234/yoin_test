<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Unitcustomfieldvalue
 *
 * @ORM\Table(name="unitcustomfieldvalue", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_27c47ed8bf396750", columns={"id"}), @ORM\UniqueConstraint(name="customfield_unit", columns={"customfield_id", "unit_id"})}, indexes={@ORM\Index(name="idx_27c47ed8f8bd700d", columns={"unit_id"}), @ORM\Index(name="idx_27c47ed8ce50ed56", columns={"customfield_id"})})
 * @ORM\Entity
 */
class Unitcustomfieldvalue
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="unitcustomfieldvalue_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=false)
     */
    private $value;

    /**
     * @var \Unitcustomfield
     *
     * @ORM\ManyToOne(targetEntity="Unitcustomfield")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customfield_id", referencedColumnName="id")
     * })
     */
    private $customfield;

    /**
     * @var \Unit
     *
     * @ORM\ManyToOne(targetEntity="Unit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unit_id", referencedColumnName="id")
     * })
     */
    private $unit;

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

    public function getCustomfield(): ?Unitcustomfield
    {
        return $this->customfield;
    }

    public function setCustomfield(?Unitcustomfield $customfield): self
    {
        $this->customfield = $customfield;

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }


}
