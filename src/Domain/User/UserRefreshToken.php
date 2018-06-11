<?php

namespace App\Domain\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class UserRefreshToken
{
    private const EXPIRATION_TIME = 30;  // Days

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    private $token;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $expirationTime;

    public function __construct()
    {
        $this->expirationTime = new \DateTime('+'.self::EXPIRATION_TIME.' day');
        $strong = true;
        $this->token = bin2hex(openssl_random_pseudo_bytes(100, $strong));
    }

    public function isValid(): bool
    {
        return $this->expirationTime > new \DateTime();
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
