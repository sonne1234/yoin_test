<?php

namespace App\Domain\Unit\Exception;

class PetsLimitExceedException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Pets can not be more than 20 in the unit.');
    }
}
