<?php

namespace App\Service\User;

use App\Service\AbstractHandler;
use App\Infrastructure\Client\CentrifugoClient;

class GetWebsocketTokenHandler extends AbstractHandler
{
    private $centrifugoClient;

    public function __construct(
        CentrifugoClient $centrifugoClient
    ) {
        $this->centrifugoClient = $centrifugoClient;
    }

    public function __invoke()
    {
        return $this->centrifugoClient->getConnectionData($this->currentUser);
    }
}
