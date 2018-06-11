<?php

namespace App\Domain\Unit\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\Resident\Transformer\ResidentShortInfoTransformer;
use App\Domain\Unit\Unit;

class UnitTransformer extends DomainTransformer
{
    /**
     * @var bool
     */
    private $addResidentsList;

    /**
     * @var bool
     */
    private $isShowResidentNumber;

    public function __construct(bool $addResidentsList = false, bool $isShowResidentNumber = true)
    {
        $this->addResidentsList = $addResidentsList;
        $this->isShowResidentNumber = $isShowResidentNumber;
    }

    /**
     * @param Unit $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return array_merge(
            $entity->toArray(false, $this->isShowResidentNumber),
            $this->addResidentsList
                ? [
                    'residents' => (new ResidentShortInfoTransformer())
                        ->setIsShowResidentNumber($this->isShowResidentNumber)
                        ->transform($entity->getOrderedResidents()),
                ]
                : []
        );
    }
}
