<?php

namespace App\Domain\Condo\Transformer;

use App\Domain\Condo\CondoBuilding;
use App\Domain\DomainTransformer;
use App\Domain\Resident\ResidentRepository;
use App\Domain\Unit\Unit;

class CondoBuildingStatsTransformer extends DomainTransformer
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
     * @param CondoBuilding $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'unitsCount' => $entity->getUnitsCount(),
            'freeUnitsCount' => $this->residentRepository->getCondoBuildingFreeUnitsCount($entity),
            'residentsCount' => array_reduce(
                $entity->getUnits()->toArray(),
                function ($result, $unit) {
                    /* @var Unit $unit */
                    return $result + $unit->getNotDeactivatedResidentsCount();
                },
                0
            ),
        ];
    }
}
