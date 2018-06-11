<?php

namespace App\Infrastructure\Client\Exception;

class InvalidDataReceivedException extends RemoteApiException
{
    public function __construct()
    {
        parent::__construct('Invalid data');
    }
}
