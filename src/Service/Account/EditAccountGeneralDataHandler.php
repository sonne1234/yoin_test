<?php

namespace App\Service\Account;

use App\Request\Account\EditAccountGeneralDataRequest;
use App\Service\AbstractHandler;
use App\Service\ImageFinderTrait;
use App\Domain\Account\Account;
use App\Domain\Account\AccountGeneralData;
use App\Domain\Account\Exception\AccountNotFoundException;
use App\Domain\Account\Transformer\AccountTransformer;
use App\Domain\DomainRepository;

class EditAccountGeneralDataHandler extends AbstractHandler
{
    use ImageFinderTrait;

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
        AccountTransformer $transformer,
        DomainRepository $imageRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->transformer = $transformer;
        $this->imageRepository = $imageRepository;
    }

    /**
     * @param EditAccountGeneralDataRequest $request
     * @param Account|string|null           $account
     *
     * @return AccountGeneralData|array
     */
    public function __invoke(EditAccountGeneralDataRequest $request, $account = null)
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

        $data = new AccountGeneralData(
            $request->companyName,
            $request->streetName,
            $request->buildingNumber,
            $request->neighborhoodName,
            $request->zipCode,
            $request->city,
            $request->state,
            $request->country,
            $request->phone,
            $request->altPhone,
            $request->email,
            $request->contactFirstName,
            $request->contactLastName,
            $request->contactEmail,
            $request->contactPhone,
            $request->contactAltPhone,
            $this->replaceImage(
                $request->logoUrl,
                $account ? $account->getImage() : null
            )
        );

        if (is_null($account)) {
            return $data;
        } else {
            $account->setAccountGeneralData($data);

            return $this->transformer->transform($account);
        }
    }
}
