<?php

namespace App\Domain\Resident\Transformer;

use App\Domain\Platform\Transformer\PlatformAdminTransformer;
use App\Domain\Resident\Resident;

class ResidentTransformer extends PlatformAdminTransformer
{
    private $isShowCustomFieldsOnlyObservableForResidents;
    private $isSkipCustomFields;
    private $isShowResidentNumber = true;

    public function __construct(
        bool $isShowCustomFieldsOnlyObservableForResidents = false,
        bool $isSkipCustomFields = false
    ) {
        $this->isShowCustomFieldsOnlyObservableForResidents = $isShowCustomFieldsOnlyObservableForResidents;
        $this->isSkipCustomFields = $isSkipCustomFields;
    }

    public function setIsShowResidentNumber(bool $isShowResidentNumber): self
    {
        $this->isShowResidentNumber = $isShowResidentNumber;

        return $this;
    }

    /**
     * @param Resident $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        $res = $entity->toArray($this->isShowCustomFieldsOnlyObservableForResidents, $this->isSkipCustomFields)
            + parent::transformOneEntity($entity);

        if (!$this->isShowResidentNumber) {
            foreach (['homePhone', 'cellPhone'] as $field) {
                if (isset($res[$field])) {
                    $res[$field] = '';
                }
            }
        }

        return $res;
    }
}
