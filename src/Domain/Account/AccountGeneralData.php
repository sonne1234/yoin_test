<?php

namespace App\Domain\Account;

use App\Domain\Common\Image;
use App\Domain\ToArrayTransformTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class AccountGeneralData
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
        'phone',
        'email',
        'contactFirstName',
        'contactLastName',
        'contactEmail',
        'contactPhone',
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
    private $phone;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $altPhone;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $contactFirstName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $contactLastName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $contactEmail;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $contactPhone;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $contactAltPhone;

    /** @var Image|null */
    private $image;

    public function __construct(
        string $companyName,
        string $streetName,
        string $buildingNumber,
        string $neighborhoodName,
        string $zipCode,
        string $city,
        string $state,
        string $country,
        string $phone,
        string $altPhone,
        string $email,
        string $contactFirstName,
        string $contactLastName,
        string $contactEmail,
        string $contactPhone,
        string $contactAltPhone,
        ?Image $image
    ) {
        $this->companyName = $companyName;
        $this->streetName = $streetName;
        $this->buildingNumber = $buildingNumber;
        $this->neighborhoodName = $neighborhoodName;
        $this->zipCode = $zipCode;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
        $this->phone = $phone;
        $this->altPhone = $altPhone;
        $this->email = $email;
        $this->contactFirstName = $contactFirstName;
        $this->contactLastName = $contactLastName;
        $this->contactEmail = $contactEmail;
        $this->contactPhone = $contactPhone;
        $this->contactAltPhone = $contactAltPhone;
        $this->image = $image;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }
}
