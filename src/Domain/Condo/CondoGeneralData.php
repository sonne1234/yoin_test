<?php

namespace App\Domain\Condo;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class CondoGeneralData
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $streetName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $neighborhoodName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $zipCode;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $state;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $country;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @var array
     * @ORM\Column(type="array", nullable=false)
     */
    private $customFields;

    public function __construct(
        string $name,
        string $streetName,
        string $neighborhoodName,
        string $zipCode,
        string $city,
        string $state,
        string $country,
        string $description,
        array $customFields
    ) {
        $this->name = $name;
        $this->streetName = $streetName;
        $this->neighborhoodName = $neighborhoodName;
        $this->zipCode = $zipCode;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
        $this->description = $description;
        $this->customFields = $customFields;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
