<?php

namespace App\Infrastructure\Client\Exception;

class RemoteApiException extends \UnexpectedValueException
{
    public function __construct(string $response)
    {
        parent::__construct("There was unexpected response from remote API: $response");
    }
}
