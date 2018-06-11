<?php

namespace App\Domain\Unit\Exception;

class UnitNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Unit is not found.');
    }
}
