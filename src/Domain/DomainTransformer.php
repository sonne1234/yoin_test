<?php

namespace App\Domain;

abstract class DomainTransformer
{
    public function transform($entity): array
    {
        if (is_iterable($entity)) {
            $res = [];
            foreach ($entity as $one) {
                $res[] = $this->transformOneEntity($one);
            }

            return $res;
        } else {
            return $this->transformOneEntity($entity);
        }
    }

    abstract protected function transformOneEntity($entity): array;

    protected function transformMoneyToFloat(int $amount): float
    {
        return (float)bcdiv($amount / 100, 1, 2);
    }
}
