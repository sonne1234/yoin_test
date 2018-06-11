<?php

namespace App\Domain\Device\Criteria;

use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class BySessionIdCriteria implements DomainCriteria
{
    private $sessionId;

    /**
     * BySessionIdCriteria constructor.
     *
     * @param $sessionId
     */
    public function __construct($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    public function create(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('sessionId', $this->sessionId));
    }
}
