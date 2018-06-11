<?php

namespace App\Domain\Unit\Exception;

class PetNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Pet is not found.');
    }
}
