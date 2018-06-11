<?php

namespace App\Domain\EntryInstruction\Transformer;

use App\Domain\DomainTransformer;
use App\Domain\EntryInstruction\EntryInstruction;
use App\Domain\Resident\Transformer\ResidentShortInfoTransformer;
use App\Domain\User\Transformer\UserShortInfoTransformer;

class EntryInstructionTransformer extends DomainTransformer
{
    private $isShowResidentNumber = true;

    public function setIsShowResidentNumber(bool $isShowResidentNumber): self
    {
        $this->isShowResidentNumber = $isShowResidentNumber;

        return $this;
    }

    /**
     * @param EntryInstruction $entity
     *
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'resident' => (new ResidentShortInfoTransformer())
                ->setIsShowResidentNumber($this->isShowResidentNumber)
                ->transform($entity->getResident()),
            'periodStart' => $entity->getPeriodStart()->format('Y-m-d'),
            'periodEnd' => $entity->getPeriodEnd()
                ? $entity->getPeriodEnd()->format('Y-m-d')
                : '',
            'visitorFirstName' => $entity->getVisitorFirstName(),
            'visitorLastName' => $entity->getVisitorLastName(),
            'visitorEmail' => $entity->getVisitorEmail(),
            'visitorCompany' => $entity->getVisitorCompany(),
            'visitorAdditionalInfo' => $entity->getVisitorAdditionalInfo(),
            'image' => $entity->getImage()
                ? $entity->getImage()->toArray()
                : null,
            'createdAt' => $entity->getCreatedAt()->format(DATE_ATOM),
            'condoId' => $entity->getCondo()->getId(),
            'status' => $entity->getStatus(),
            'isCancelledByAdmin' => $entity->getIsCancelledByAdmin(),
            'createdBy' => (new UserShortInfoTransformer())->transform(
                $entity->getCreatedBy()
            ),
            'logEntry' => $entity->getLatestLogEntry()
                ? (new EntryInstructionLogTransformer())->transform($entity->getLatestLogEntry())
                : null,
            'isForToday' => $entity->isForToday(),
            'isSingleEntry' => $entity->isSingleEntry(),
            'enterDate' => $entity->getEnterDate() ? $entity->getEnterDate()->format(DATE_ATOM) : null,
            'exitDate' => $entity->getExitDate() ? $entity->getExitDate()->format(DATE_ATOM) : null,
        ];
    }
}
