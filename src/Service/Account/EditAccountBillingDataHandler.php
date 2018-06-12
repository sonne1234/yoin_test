<?php

namespace App\Service\Account;

use App\Request\Account\EditAccountBillingDataRequest;
use App\Service\AbstractHandler;
use App\Domain\Account\Account;
use App\Domain\Account\AccountBillingData;
use App\Domain\Account\Exception\AccountNotFoundException;
use App\Domain\Account\Transformer\AccountTransformer;
use App\Domain\DomainRepository;

class EditAccountBillingDataHandler extends AbstractHandler
{
    /**
     * @var DomainRepository
     */
    private $accountRepository;

    /**
     * @var AccountTransformer
     */
    private $transformer;

    public function __construct(
        DomainRepository $accountRepository,
        AccountTransformer $transformer
    ) {
        $this->accountRepository = $accountRepository;
        $this->transformer = $transformer;
    }

    /**
     * @param EditAccountBillingDataRequest $request
     * @param Account|string|null           $account
     *
     * @return AccountBillingData|array
     */
    public function __invoke(EditAccountBillingDataRequest $request, $account = null)
    {
        if (!is_null($account) &&
            !($account instanceof Account) &&
            !($account = $this->accountRepository->get((string) $account))
        ) {
            throw new AccountNotFoundException();
        }

        if ($account) {
            $this->checkAccess([$account]);
        }

        $data = new AccountBillingData(
            $request->companyName,
            $request->streetName,
            $request->buildingNumber,
            $request->neighborhoodName,
            $request->zipCode,
            $request->city,
            $request->state,
            $request->country,
            $request->taxIdNumber
        );

        if (is_null($account)) {
            return $data;
        } else {
            $account->setAccountBillingData($data);

            return $this->transformer->transform($account);
        }
    }
}
