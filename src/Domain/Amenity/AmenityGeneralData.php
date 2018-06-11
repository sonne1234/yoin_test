<?php

namespace App\Domain\Amenity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class AmenityGeneralData
{
    const CATEGORY_ENT = 4;
    const CATEGORY_SPORT = 6;
    const CATEGORY_SPA = 1;
    const CATEGORY_GARDEN = 5;
    const CATEGORY_ELEVATOR = 3;
    const CATEGORY_COMMUNITY = 2;
    const CATEGORIES_LIST = [
        self::CATEGORY_ENT => [
            'name' => 'Entertainment',
            'label' => 'ENTERTAINMENT',
        ],
        self::CATEGORY_SPORT => [
            'name' => 'Sports',
            'label' => 'SPORTS',
        ],
        self::CATEGORY_SPA => [
            'name' => 'Beauty and Spa',
            'label' => 'BEAUTY_AND_SPA',
        ],
        self::CATEGORY_GARDEN => [
            'name' => 'Gardens and grills',
            'label' => 'GARDENS_AND_GRILLS',
        ],
        self::CATEGORY_ELEVATOR => [
            'name' => 'Elevators',
            'label' => 'ELEVATORS',
        ],
        self::CATEGORY_COMMUNITY => [
            'name' => 'Community centers',
            'label' => 'COMMUNITY_CENTERS',
        ],
    ];

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $phoneNumber;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $nameLowerCase = '';

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $category;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $address;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $capacity;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $rules;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $mainImageId = '';

    public function __construct(
        string $name,
        int $category,
        string $address,
        int $capacity,
        string $description,
        string $rules,
        string $mainImageId,
        string $phoneNumber
    ) {
        $this->name = trim($name);
        $this->nameLowerCase = mb_strtolower($this->name);
        $this->category = $category;
        $this->address = $address;
        $this->capacity = $capacity;
        $this->description = $description;
        $this->rules = $rules;
        $this->mainImageId = $mainImageId;
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getNameLowerCase(): string
    {
        return $this->nameLowerCase;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'category' => [
                'id' => $this->category,
                'name' => self::CATEGORIES_LIST[$this->category]['name'],
                'label' => self::CATEGORIES_LIST[$this->category]['label'],
            ],
            'address' => $this->address,
            'capacity' => $this->capacity,
            'description' => $this->description,
            'rules' => $this->rules,
            'mainImageId' => $this->mainImageId,
            'phoneNumber' => $this->phoneNumber,
        ];
    }
}
