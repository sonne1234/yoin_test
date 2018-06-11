<?php

namespace App\Domain;

use Doctrine\Common\Collections\Criteria;

interface DomainCriteria
{
    public function create(): Criteria;
}
