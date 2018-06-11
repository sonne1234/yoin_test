<?php

namespace App\Domain\User\Criteria;

use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class UserByRefreshTokenCriteria implements DomainCriteria
{
    /**
     * @var string
     */
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function create(): Criteria
    {
        return Criteria::create()->where(
            Criteria::expr()->eq('refreshToken.token', $this->token)
        );
    }
}
