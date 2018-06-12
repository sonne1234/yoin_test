<?php

namespace App\Service\Account;

use App\Service\AbstractHandler;
use App\Domain\Account\Account;
use App\Domain\Account\Exception\AccountNotFoundException;
use App\Domain\Condo\Transformer\CondoAdminTransformer;
use App\Domain\DomainRepository;
use App\Domain\User\Criteria\ActivatedUserCriteria;
use App\Domain\User\Criteria\DeactivatedUserCriteria;
use App\Domain\User\Criteria\NotInitializedUserCriteria;

class CondoAdminsFetcher extends AbstractHandler
{
    /**
     * @var DomainRepository
     */
    private $accountRepository;

    /**
     * @var CondoAdminTransformer
     */
    private $transformer;

    public function __construct(
        DomainRepository $accountRepository,
        CondoAdminTransformer $transformer
    ) {
        $this->accountRepository = $accountRepository;
        $this->transformer = $transformer;
    }

    public function __invoke(string $accountId): array
    {
        /** @var Account $account */
        if (!$account = $this->accountRepository->get($accountId)) {
            throw new AccountNotFoundException();
        }

        $this->checkAccess([$account]);

        return [
            'pending' => $this->transformer->transform(
                $account->getCondoAdminsByCriteria(new NotInitializedUserCriteria())->toArray()
            ),
            'activated' => $this->transformer->transform(
                $account->getCondoAdminsByCriteria(new ActivatedUserCriteria())->toArray()
            ),
            'deactivated' => $this->transformer->transform(
                $account->getCondoAdminsByCriteria(new DeactivatedUserCriteria())->toArray()
            ),
        ];
    }
}
