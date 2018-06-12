<?php

namespace App\Service\Account;

use App\Request\Account\CreateAccountAdminRequest;
use App\Request\Account\CreateAccountRequest;
use App\Request\Account\EditAccountBillingDataRequest;
use App\Request\Account\EditAccountGeneralDataRequest;
use App\Service\AbstractHandler;
use App\Domain\Account\Account;
use App\Domain\Account\Transformer\AccountTransformer;
use App\Domain\DomainRepository;

class CreateAccountHandler extends AbstractHandler
{
    /**
     * @var DomainRepository
     */
    private $accountRepository;

    /**
     * @var AccountTransformer
     */
    private $transformer;

    /**
     * @var CreateAccountAdminHandler
     */
    private $createAccountAdminHandler;

    /**
     * @var EditAccountBillingDataHandler
     */
    private $editAccountBillingDataHandler;

    /**
     * @var EditAccountGeneralDataHandler
     */
    private $editAccountGeneralDataHandler;

    public function __construct(
        DomainRepository $accountRepository,
        AccountTransformer $transformer,
        CreateAccountAdminHandler $createAccountAdminHandler,
        EditAccountBillingDataHandler $editAccountBillingDataHandler,
        EditAccountGeneralDataHandler $editAccountGeneralDataHandler
    ) {
        $this->accountRepository = $accountRepository;
        $this->transformer = $transformer;
        $this->createAccountAdminHandler = $createAccountAdminHandler;
        $this->editAccountBillingDataHandler = $editAccountBillingDataHandler;
        $this->editAccountGeneralDataHandler = $editAccountGeneralDataHandler;
    }

    public function __invoke(CreateAccountRequest $request): array
    {
        $account = new Account(
            ($this->createAccountAdminHandler)(
                (new CreateAccountAdminRequest())->setPayload($request->accountAdmin),
                null,
                true
            ),
            ($this->editAccountBillingDataHandler)(
                (new EditAccountBillingDataRequest())->setPayload($request->accountBillingData)
            ),
            ($this->editAccountGeneralDataHandler)(
                (new EditAccountGeneralDataRequest())->setPayload($request->accountGeneralData)
            )
        );

        $this->accountRepository->add($account);

        return $this->transformer->transform($account);
    }
}
