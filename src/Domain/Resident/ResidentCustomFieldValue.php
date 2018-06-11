<?php

namespace App\Domain\Resident;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"customfield_id", "resident_id"})
 *  }
 * )
 */
class ResidentCustomFieldValue
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
     * @var ResidentCustomField
     * @ORM\ManyToOne(targetEntity="App\Domain\Resident\ResidentCustomField", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $customField;

    /**
     * @var Resident
     * @ORM\ManyToOne(targetEntity="App\Domain\Resident\Resident", inversedBy="customFieldValues")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $resident;

    public function __construct(ResidentCustomField $customField, Resident $resident, string $value = '')
    {
        $this->id = Uuid::uuid4()->toString();
        $this->value = $value;
        $this->customField = $customField;
        $this->resident = $resident;
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
