<?php

namespace App\Domain\Device\Criteria;

use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class ByTokenCriteria implements DomainCriteria
{
    private $token;

    /**
     * AllByTokenCriteria constructor.
     *
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function create(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('token', $this->token));
    }
}
