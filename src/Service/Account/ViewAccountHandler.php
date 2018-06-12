<?php

namespace App\Service\Account;

use App\Service\AbstractHandler;
use App\Domain\Account\Exception\AccountNotFoundException;
use App\Domain\Account\Transformer\AccountTransformer;
use App\Domain\DomainRepository;

class ViewAccountHandler extends AbstractHandler
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

    public function __invoke(string $accountId): array
    {
        if (!$account = $this->accountRepository->get($accountId)) {
            throw new AccountNotFoundException();
        }

        $this->checkAccess([$account]);

        return $this->transformer->transform($account);
    }
}
