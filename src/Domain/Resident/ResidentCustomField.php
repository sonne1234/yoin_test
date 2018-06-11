<?php

namespace App\Domain\Resident;

use App\Domain\Condo\Condo;
use App\Domain\Resident\Exception\ResidentCustomFieldWithTheSameNameExistsException;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 */
class ResidentCustomField
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
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $nameLowerCase;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isRequired;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isObservable;

    /**
     * @var Condo|null
     * @ORM\ManyToOne(targetEntity="App\Domain\Condo\Condo", inversedBy="residentCustomFields")
     * @ORM\JoinColumn(nullable=false)
     */
    private $condo;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getIsRequired(): bool
    {
        return $this->isRequired;
    }

    public function getNameLowerCase(): string
    {
        return $this->nameLowerCase;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function updateInfo(
        string $name,
        bool $isRequired,
        bool $isObservable
    ): self {
        $this->name = trim($name);
        $this->nameLowerCase = mb_strtolower($this->name);
        $this->isRequired = $isRequired;
        $this->isObservable = $isObservable;

        if ($this->condo->isResidentCustomFieldWithTheSameNameExists($this->name, $this->id)) {
            throw new ResidentCustomFieldWithTheSameNameExistsException();
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'condoId' => $this->condo
                ? $this->condo->getId()
                : null,
            'name' => $this->name,
            'isRequired' => $this->isRequired,
            'isObservable' => $this->isObservable,
        ];
    }

    public function setCondo(Condo $condo): self
    {
        $this->condo = $condo;

        return $this;
    }
}
