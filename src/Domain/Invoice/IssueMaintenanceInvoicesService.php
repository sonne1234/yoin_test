<?php

namespace App\Domain\Invoice;

use App\Application\Request\Condo\EditCondoMaintenanceDataRequest;
use App\Application\Request\Invoice\CreateMaintenanceInvoiceRequest;
use App\Application\Service\Condo\EditCondoMaintenanceDataHandler;
use App\Application\Service\Invoice\CreateInvoiceHandler;
use App\Domain\Condo\Condo;
use App\Domain\Condo\Criteria\CondoByMaintenanceFeeCriteria;
use App\Domain\DomainRepository;

use App\Domain\Unit\Unit;
use Doctrine\ORM\EntityManager;

class IssueMaintenanceInvoicesService
{

    /**
     * @var DomainRepository
     */
    private $condoRepository;

    private $createInvoiceHandler;

    private $editCondoMaintenanceDataHandler;

    public function __construct(
        DomainRepository $condoRepository,
        CreateInvoiceHandler $createInvoiceHandler,
        EditCondoMaintenanceDataHandler $editCondoMaintenanceDataHandler
    ) {
        $this->condoRepository = $condoRepository;
        $this->createInvoiceHandler = $createInvoiceHandler;
        $this->editCondoMaintenanceDataHandler = $editCondoMaintenanceDataHandler;
    }

    /**
     * @param \DateTime $today
     * @return int
     */
    public function execute(\DateTime $today): int
    {
        $count = 0;

        /**
         * @var Condo $condo
         */
        foreach ($this->condoRepository->getCollectionByCriteria(new CondoByMaintenanceFeeCriteria($today)) as $condo) {
            if ($condo->getMaintenanceData()) {

                /**
                 * @var Unit $unit
                 */
                foreach ($condo->getFeePayingUnits() as $unit) {
                    $this->createInvoiceHandler->execute(
                        [
                            (new CreateMaintenanceInvoiceRequest())->setPayload(
                                [
                                    'amount' => $condo->getMaintenanceData()->getMaintenanceFeeSize() * 100,
                                    'unitId' => $unit->getId(),
                                    'payPeriod' => $condo->getMaintenanceData()->getPayPeriod(),
                                ]
                            ),
                        ]
                    );
                    ++$count;
                }
                $this->editCondoMaintenanceDataHandler->execute(
                    [
                        (new EditCondoMaintenanceDataRequest())->setPayload($condo->getMaintenanceData()->toArray()),
                        $condo->getId(),
                        $today,
                    ]
                );
            }
        }

        return $count;
    }
}
