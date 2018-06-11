<?php

namespace App\Domain\Account\Transformer;

use App\Domain\Account\Account;
use App\Domain\Condo\Condo;
use App\Domain\DomainTransformer;
use App\Domain\Unit\Unit;

class AccountStatisticsTransformer extends DomainTransformer
{
    /**
     * @param Account $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'condosCount' => $entity->getCondosCount(),
            'unitsCount' => array_reduce(
                $entity->getCondos()->toArray(),
                function ($result, $condo) {
                    /** @var Condo $condo */
                    return $result + $condo->getUnitsCount();
                },
                0
            ),
            'residentsCount' => $this->getResidentCount($entity, false),
            'activeResidentsCount' => $this->getResidentCount($entity, true),
        ];
    }

    private function getResidentCount(Account $entity, bool $onlyActive) : int
    {
        return array_reduce(
            $entity->getCondos()->toArray(),
            function ($result, $condo) use ($onlyActive) {
                /** @var Condo $condo */
                return $result + array_reduce(
                    $condo->getUnits()->toArray(),
                    function ($result, $unit) use ($onlyActive) {
                        /** @var Unit $unit */
                        return $result + (
                            $onlyActive
                                ? $unit->getActiveResidentsCount()
                                : $unit->getResidentsCount()
                            );
                    },
                    0
                );
            },
            0
        );
    }
}
