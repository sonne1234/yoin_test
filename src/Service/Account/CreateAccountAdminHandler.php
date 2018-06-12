<?php

namespace App\Service\Account;

use App\Request\Account\CreateAccountAdminRequest;
use App\Service\AbstractHandler;
use App\Service\ImageFinderTrait;
use App\Service\User\UserPasswordEncoder;
use App\Domain\Account\Account;
use App\Domain\Account\AccountAdmin;
use App\Domain\Account\Exception\AccountNotFoundException;
use App\Domain\Account\Transformer\AccountAdminTransformer;
use App\Domain\DomainRepository;
use App\Domain\User\Criteria\UserByEmailCriteria;
use App\Domain\User\Exception\UserWithTheSameEmailAlreadyExistsException;

class CreateAccountAdminHandler extends AbstractHandler
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
     * @var DomainRepository
     */
    private $accountAdminRepository;

    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    /**
     * @var AccountAdminTransformer
     */
    private $transformer;

    public function __construct(
        DomainRepository $accountRepository,
        DomainRepository $userRepository,
        AccountAdminTransformer $transformer,
        UserPasswordEncoder $encoder,
        DomainRepository $imageRepository,
        DomainRepository $accountAdminRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->userRepository = $userRepository;
        $this->transformer = $transformer;
        $this->passwordEncoder = $encoder;
        $this->imageRepository = $imageRepository;
        $this->accountAdminRepository = $accountAdminRepository;
    }

    /**
     * @param CreateAccountAdminRequest $request
     * @param Account|string|null       $account
     * @param bool                      $isPrimaryAdmin
     *
     * @return AccountAdmin|array
     */
    public function __invoke(CreateAccountAdminRequest $request, $account = null, bool $isPrimaryAdmin = false)
    {
        if (!is_null($account) &&
            !($account instanceof Account) &&
            !($account = $this->accountRepository->get((string) $account))
        ) {
            throw new AccountNotFoundException();
        }

        if ($this->userRepository->getOneByCriteria(
            new UserByEmailCriteria($request->email)
        )) {
            throw new UserWithTheSameEmailAlreadyExistsException();
        }

        if ($account) {
            $this->checkAccess([$account]);
        }

        $user = new AccountAdmin(
            $request->email,
            $this->passwordEncoder->encode(),
            $request->firstName,
            $request->lastName,
            $this->useImage($request->image),
            $request->phone
        );

        if ($isPrimaryAdmin) {
            $user->markAsPrimary();
        }

        if (is_null($account)) {
            return $user;
        } else {
            $account->addAccountAdmin($user);
            $this->accountAdminRepository->add($user);

            return $this->transformer->transform($user);
        }
    }
}
