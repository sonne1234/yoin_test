<?php

namespace App\Domain\Condo\Transformer;

use App\Domain\Condo\CondoMaintenanceData;
use App\Domain\DomainTransformer;

class CondoMaintenanceTransformer extends DomainTransformer
{
    /**
     * @param CondoMaintenanceData $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        $values = $entity->toArray();
        $values['maintenanceFeeSize'] = $values['maintenanceFeeSize'];
        $values['nextInvoiceDate'] = isset($values['nextInvoiceDate']) ? $values['nextInvoiceDate']->format(
            DATE_ATOM
        ) : null;

        return $values;
    }
}
