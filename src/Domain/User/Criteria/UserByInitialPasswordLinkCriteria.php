<?php

namespace App\Domain\User\Criteria;

use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class UserByInitialPasswordLinkCriteria implements DomainCriteria
{
    /**
     * @var string
     */
    protected $link;

    public function __construct(string $link)
    {
        $this->link = $link;
    }

    public function create(): Criteria
    {
        return Criteria::create()->where(
            Criteria::expr()->eq('initialPasswordLink', $this->link)
        );
    }
}
