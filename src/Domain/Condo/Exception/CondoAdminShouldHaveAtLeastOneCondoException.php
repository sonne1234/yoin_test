<?php

namespace App\Domain\Condo\Exception;

class CondoAdminShouldHaveAtLeastOneCondoException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Condo admin should have at least one condo.');
    }
}
