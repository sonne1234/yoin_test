<?php

namespace App\Infrastructure\Client\Exception;

class PagamobilException extends RemoteApiException
{
    public function __construct(string $code)
    {
        parent::__construct("There was unexpected response from remote payment system. Error code: $code");
    }
}
