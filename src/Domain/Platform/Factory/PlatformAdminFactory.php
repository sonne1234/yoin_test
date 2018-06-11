<?php

namespace App\Domain\Platform\Factory;

use App\Application\Service\User\UserPasswordEncoder;
use App\Domain\Platform\PlatformAdmin;

class PlatformAdminFactory
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

        return (new PlatformAdmin($email, $password, $firstName, $lastName, $image, $phone))
            ->setPassword($password)
            ->activate();
    }
}
