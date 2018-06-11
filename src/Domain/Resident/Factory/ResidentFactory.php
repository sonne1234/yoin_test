<?php

namespace App\Domain\Resident\Factory;

use App\Application\Service\User\UserPasswordEncoder;
use App\Domain\Resident\Resident;

class ResidentFactory
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

        return (new Resident($email, $password, $firstName, $lastName, $image, $phone))
            ->setPassword($password)
            ->activate();
    }
}
