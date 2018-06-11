<?php

namespace App\Domain\Condo\Transformer;

use App\Domain\Condo\Condo;
use App\Domain\DomainTransformer;
use App\Domain\Resident\ResidentRepository;

class CondoOverallTransformer extends DomainTransformer
{
    /**
     * @var ResidentRepository
     */
    private $residentRepository;

    public function setResidentRepository(ResidentRepository $repository): self
    {
        $this->residentRepository = $repository;

        return $this;
    }

    /**
     * @param Condo $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'accountId' => $entity->getAccount()
                ? $entity->getAccount()->getId()
                : null,
            'name' => $entity->getName(),
            'staffCount' => 0,
            'monthlyRevenue' => 0.00,
            'pendingPayments' => 0.00,
            'createdAt' => $entity->getCreatedAt()->format(\DateTime::ATOM),
        ]
            + (new CondoUnitsStatsTransformer())
                ->setResidentRepository($this->residentRepository)
                ->transform($entity);
    }
}
