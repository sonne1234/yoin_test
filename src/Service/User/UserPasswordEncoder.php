<?php

namespace App\Service\User;

use App\Domain\User\UserIdentity;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserPasswordEncoder
{
    /**
     * @var EncoderFactoryInterface
     */
    private $passwordEncoder;

    private $passwordCache = [];

    public function __construct(EncoderFactoryInterface $encoder)
    {
        $this->passwordEncoder = $encoder;
    }

    public function encode(string $plainPassword = null): string
    {
//        Cache passwords to speed up fixtures loading
        if (empty($this->passwordCache[$plainPassword])) {
            $passwordHash = $this
                ->passwordEncoder
                ->getEncoder(UserIdentity::class)
                ->encodePassword(
                    $plainPassword ?? str_shuffle(implode('', range('a', 'z')).implode('', range('0', '9'))),
                    ''
                );
            $this->passwordCache[$plainPassword] = $passwordHash;
        }

        return $this->passwordCache[$plainPassword];
    }

    public function isPasswordValid(string $encoded, string $raw): bool
    {
        return $this
            ->passwordEncoder
            ->getEncoder(UserIdentity::class)
            ->isPasswordValid($encoded, $raw, null);
    }
}
