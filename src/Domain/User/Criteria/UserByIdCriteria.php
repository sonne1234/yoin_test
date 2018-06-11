<?php

namespace App\Domain\User\Criteria;

use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class UserByIdCriteria implements DomainCriteria
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function create(): Criteria
    {
        return Criteria::create()->where(
            Criteria::expr()->eq('id', $this->id)
        );
    }
}
