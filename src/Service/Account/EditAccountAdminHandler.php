<?php

namespace App\Service\Account;

use App\Request\Account\EditAccountAdminRequest;
use App\Service\AbstractHandler;
use App\Service\ImageFinderTrait;
use App\Domain\Account\Account;
use App\Domain\Account\Exception\AccountNotFoundException;
use App\Domain\Account\Transformer\AccountAdminTransformer;
use App\Domain\DomainRepository;
use App\Domain\User\Criteria\UserByEmailCriteria;
use App\Domain\User\Exception\UserWithTheSameEmailAlreadyExistsException;

class EditAccountAdminHandler extends AbstractHandler
{
    use ImageFinderTrait;

    /**
     * @var DomainRepository
     */
    private $accountRepository;

    /**
     * @var DomainRepository
     */
    private $userRepository;

    /**
     * @var AccountAdminTransformer
     */
    private $transformer;

    public function __construct(
        DomainRepository $accountRepository,
        DomainRepository $userRepository,
        AccountAdminTransformer $transformer,
        DomainRepository $imageRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->userRepository = $userRepository;
        $this->transformer = $transformer;
        $this->imageRepository = $imageRepository;
    }

    public function __invoke(EditAccountAdminRequest $request, string $accountId, string $accountAdminId)
    {
        /** @var Account $account */
        if (!$account = $this->accountRepository->get($accountId)) {
            throw new AccountNotFoundException();
        }

        $user = $account->getAccountAdmin($accountAdminId);

        $this->checkAccess([$account, $user]);

        // check email is already taken
        if (($res = $this->userRepository->getOneByCriteria(new UserByEmailCriteria($request->email)))
            && $res !== $user
        ) {
            throw new UserWithTheSameEmailAlreadyExistsException();
        }

        $user->updateInfo(
            $request->email,
            $request->firstName,
            $request->lastName,
            $this->replaceImage($request->image, $user->getImage()),
            $request->phone
        );

        return $this->transformer->transform($user);
    }
}
