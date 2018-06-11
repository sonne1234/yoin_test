<?php

namespace App\Domain\Condo\Exception;

class CondoBuildingNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Condo building is not found.');
    }
}
