<?php

namespace App\Domain\ServiceRequest\Exception;

class ServiceRequestNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Service Request is not found.');
    }
}
