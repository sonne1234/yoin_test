<?php

namespace App\Domain\Resident\Exception;

class NoPrimeResidentFoundException extends \DomainException
{
    public function __construct($message = 'You can not add a subuser till there is no prime user.')
    {
        parent::__construct($message);
    }
}
