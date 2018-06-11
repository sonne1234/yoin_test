<?php

namespace App\Domain;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;

trait GetEntityByIdInCollectionTrait
{
    /**
     * @param string          $id
     * @param ArrayCollection $collection
     * @param bool            $throwExceptionIfNotFound
     * @param string          $exceptionClassName
     *
     * @throws \Exception
     */
    protected function getEntityByIdInCollection(
        string $id,
        Selectable $collection,
        bool $throwExceptionIfNotFound,
        string $exceptionClassName
    ) {
        $res = $collection
            ->matching(
                Criteria::create()->where(
                    Criteria::expr()->eq('id', $id)
                )
            );

        if ($throwExceptionIfNotFound && !$res->count()) {
            throw new $exceptionClassName();
        }

        return $res->count()
            ? $res->first()
            : null;
    }
}
