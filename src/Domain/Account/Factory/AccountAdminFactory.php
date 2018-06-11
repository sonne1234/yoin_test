<?php

namespace App\Domain\Account\Factory;

use App\Application\Service\User\UserPasswordEncoder;
use App\Domain\Account\AccountAdmin;

class AccountAdminFactory
{
    /** @var UserPasswordEncoder */
    private $encoder;

    /**
     * UserFactory constructor.
     *
     * @param UserPasswordEncoder $encoder
     */
    public function __construct(UserPasswordEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    public function create($email, $plainPassword, $firstName, $lastName, $image, $phone, $primary = false)
    {
        $password = $this->encoder->encode($plainPassword);
        /** @var AccountAdmin $accountAdmin */
        $accountAdmin = (new AccountAdmin($email, $password, $firstName, $lastName, $image, $phone))
            ->setPassword($password)
            ->activate();
        if ($primary) {
            $accountAdmin->markAsPrimary();
        }

        return $accountAdmin;
    }
}
