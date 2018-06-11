<?php

namespace App\Domain\Condo;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class CondoBillingData
{
    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $companyName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $streetName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $buildingNumber;

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
     * @ORM\Column(type="string", nullable=false)
     */
    private $taxIdNumber;

    /**
     * @var array
     * @ORM\Column(type="array", nullable=false)
     */
    private $customFields;

    public function __construct(
        string $companyName,
        string $streetName,
        string $buildingNumber,
        string $neighborhoodName,
        string $zipCode,
        string $city,
        string $state,
        string $country,
        string $taxIdNumber,
        array $customFields
    ) {
        $this->companyName = $companyName;
        $this->streetName = $streetName;
        $this->buildingNumber = $buildingNumber;
        $this->neighborhoodName = $neighborhoodName;
        $this->zipCode = $zipCode;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
        $this->taxIdNumber = $taxIdNumber;
        $this->customFields = $customFields;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function getAddress(): array
    {
        return array_intersect_key(
            $this->toArray(),
            array_fill_keys(['buildingNumber', 'streetName', 'zipCode', 'city', 'state', 'country', 'neighborhoodName'], null)
        );
    }
}
