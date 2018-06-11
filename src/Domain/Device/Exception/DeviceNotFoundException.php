<?php

namespace App\Domain\Device\Exception;

class DeviceNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Device is not found.');
    }
}
