<?php

namespace App\Domain\Unit;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="customfield_unit", columns={"customfield_id", "unit_id"})
 *  }
 * )
 */
class UnitCustomFieldValue
{
    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $value;

    /**
     * @var UnitCustomField
     * @ORM\ManyToOne(targetEntity="App\Domain\Unit\UnitCustomField", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $customField;

    /**
     * @var Unit
     * @ORM\ManyToOne(targetEntity="App\Domain\Unit\Unit", inversedBy="customFieldValues")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $unit;

    public function __construct(UnitCustomField $customField, Unit $unit, string $value = '')
    {
        $this->id = Uuid::uuid4()->toString();
        $this->value = $value;
        $this->customField = $customField;
        $this->unit = $unit;
    }

    public function toArray(): array
    {
        return ['value' => $this->value] + $this->customField->toArray();
    }

    public function getCustomFieldNameLowerCase(): string
    {
        return $this->customField->getNameLowerCase();
    }

    public function getCustomFieldIsRequired(): bool
    {
        return $this->customField->getIsRequired();
    }

    public function getCustomFieldName(): string
    {
        return $this->customField->getName();
    }

    public function getCustomFieldId(): string
    {
        return $this->customField->getId();
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
