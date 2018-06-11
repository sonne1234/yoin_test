<?php

namespace App\Domain\Condo\Criteria;

use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class CondoByMaintenanceFeeCriteria implements DomainCriteria
{
    /**
     * @var \DateTime
     */
    private $time;

    public function __construct(\DateTime $time)
    {
        $this->time = $time;
    }

    public function create(): Criteria
    {
        return Criteria::create()
            ->where(Criteria::expr()->eq('maintenanceData.nextInvoiceDate', $this->time))
//            ->andWhere(Criteria::expr()->neq('units.residents', null))
            ->orderBy(['generalData.name' => Criteria::ASC]);
    }
}
