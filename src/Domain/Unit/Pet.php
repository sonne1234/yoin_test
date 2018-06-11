<?php

namespace App\Domain\Unit;

use App\Domain\Common\Image;
use App\Domain\GetEntityByIdInCollectionTrait;
use App\Domain\ImageRemoverEventTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Pet
{
    use
        GetEntityByIdInCollectionTrait,
        ImageRemoverEventTrait;

    const TYPES = [
        'cat',
        'dog',
        self::TYPE_OTHER,
    ];
    const TYPE_OTHER = 'other';

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
    private $type;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $typeOther;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $description;

    /**
     * @var Image|null
     * @ORM\OneToOne(targetEntity="App\Domain\Common\Image")
     * @ORM\JoinColumn(nullable=true, unique=true, onDelete="SET NULL")
     */
    protected $image;

    /**
     * @var Unit|null
     * @ORM\ManyToOne(targetEntity="App\Domain\Unit\Unit", inversedBy="pets")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $unit;

    public function __construct(Unit $unit)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->unit = $unit;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function update(string $type, string $typeOther, string $name, string $description, ?Image $image): self
    {
        $this->type = $type;
        $this->typeOther = $typeOther;
        $this->description = $description;
        $this->name = $name;
        $this->image = $image;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'typeOther' => $this->typeOther,
            'description' => $this->description,
            'name' => $this->name,
            'image' => $this->image ? $this->image->toArray() : null,
            'unitId' => $this->unit->getId(),
        ];
    }

    public function getUnit(): Unit
    {
        return $this->unit;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function unsetUnit(): self
    {
        $this->unit = null;

        return $this;
    }

    public function unsetImage(): self
    {
        $this->image = null;

        return $this;
    }
}
