<?php

namespace App\Domain\Device\Exception;

class UnsupportedPlatformException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('This platform does not support.');
    }
}
