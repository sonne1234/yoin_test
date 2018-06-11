<?php

namespace App\Domain\Account;

use App\Domain\ToArrayTransformTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class AccountBillingData
{
    use AccountDataTrait, ToArrayTransformTrait;

    private const REQUIRED_FIELDS_FOR_VERIFIED_ACCOUNT = [
        'companyName',
        'streetName',
        'buildingNumber',
        'zipCode',
        'city',
        'state',
        'country',
        'taxIdNumber',
    ];

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

    public function __construct(
        string $companyName,
        string $streetName,
        string $buildingNumber,
        string $neighborhoodName,
        string $zipCode,
        string $city,
        string $state,
        string $country,
        string $taxIdNumber
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
    }
}
