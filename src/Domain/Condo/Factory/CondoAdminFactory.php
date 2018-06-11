<?php

namespace App\Domain\Condo\Factory;

use App\Application\Service\User\UserPasswordEncoder;
use App\Domain\Condo\CondoAdmin;

class CondoAdminFactory
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

    public function create($email, $plainPassword, $firstName, $lastName, $image, $phone)
    {
        $password = $this->encoder->encode($plainPassword);

        return (new CondoAdmin($email, $password, $firstName, $lastName, $image, $phone))
            ->setPassword($password)
            ->activate();
    }
}
