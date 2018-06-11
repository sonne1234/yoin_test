<?php

namespace App\Domain\Condo\Transformer;

use App\Domain\Condo\Condo;
use App\Domain\DomainTransformer;
use App\Domain\Resident\ResidentRepository;
use App\Domain\Unit\Unit;

class CondoUnitsStatsTransformer extends DomainTransformer
{
    /**
     * @var ResidentRepository
     */
    private $residentRepository;
    /**
     * @var bool
     */
    private $isGetAllResidentsExceptDeactivatedCount = false;

    public function setResidentRepository(ResidentRepository $repository): self
    {
        $this->residentRepository = $repository;

        return $this;
    }

    public function setIsGetAllResidentsExceptDeactivatedCount(): self
    {
        $this->isGetAllResidentsExceptDeactivatedCount = true;

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
            'unitsCount' => $entity->getUnitsCount(),
            'freeUnitsCount' => $this->residentRepository->getCondoFreeUnitsCount($entity->getId()),
            'residentsCount' => array_reduce(
                $entity->getUnits()->toArray(),
                function ($result, $unit) {
                    /* @var Unit $unit */
                    return $result + (
                        $this->isGetAllResidentsExceptDeactivatedCount
                            ? $unit->getNotDeactivatedResidentsCount()
                            : $unit->getResidentsCount()
                        );
                },
                0
            ),
        ];
    }
}
