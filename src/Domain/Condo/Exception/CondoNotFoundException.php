<?php

namespace App\Domain\Condo\Exception;

class CondoNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Condo is not found.');
    }
}
