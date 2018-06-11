<?php

namespace App\Domain\Condo;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class CondoPaymentData
{
    const TYPE_NO_AFFILIATION = 1;
    const TYPE_AFFILIATION = 2;
    const TYPES = [self::TYPE_NO_AFFILIATION, self::TYPE_AFFILIATION];

    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_ERROR = 2;

    const STATUSES = [self::STATUS_ACTIVE, self::STATUS_PENDING, self::STATUS_ERROR];

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $bankId;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $businessOwnerName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $affiliationNumber;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $clabe;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $accountNumber;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $vmcBankFee;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $vmcPlatformFee;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $amexBankFee;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $amexPlatformFee;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $errorStatus;

    public function __construct(
        int $type,
        string $businessOwnerName,
        string $bankId,
        string $affiliationNumber,
        string $clabe,
        string $accountNumber,
        int $status,
        string $errorStatus,
        float $vmcBankFee,
        float $vmcPlatformFee,
        float $amexBankFee,
        float $amexPlatformFee
    ) {
        $this->type = $type;
        $this->businessOwnerName = $businessOwnerName;
        $this->bankId = $bankId;

        $this->affiliationNumber = $affiliationNumber;
        $this->clabe = $clabe;
        $this->accountNumber = $accountNumber;

        $this->vmcBankFee = $vmcBankFee;
        $this->vmcPlatformFee = $vmcPlatformFee;
        $this->amexBankFee = $amexBankFee;
        $this->amexPlatformFee = $amexPlatformFee;

        $this->status = $status;
        $this->errorStatus = $errorStatus;
    }

    public function setFee(
        float $vmcBankFee,
        float $vmcPlatformFee,
        float $amexBankFee,
        float $amexPlatformFee
    ) {
        $this->vmcBankFee = $vmcBankFee;
        $this->vmcPlatformFee = $vmcPlatformFee;
        $this->amexBankFee = $amexBankFee;
        $this->amexPlatformFee = $amexPlatformFee;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function getFees()
    {
        return [
            'vmcBankFee' => null !== $this->vmcBankFee ? $this->vmcBankFee : 2.5,
            'vmcPlatformFee' => null !== $this->vmcPlatformFee ? $this->vmcPlatformFee : 1.5,
            'amexBankFee' => null !== $this->amexBankFee ? $this->amexBankFee : 3.5,
            'amexPlatformFee' => null !== $this->amexPlatformFee ? $this->vmcBankFee : 1.5,
        ];
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }
}
