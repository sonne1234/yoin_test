<?php

namespace App\Infrastructure\Client\Centrifugo;

abstract class AbstractMessage
{
    abstract public function getChannelName();

    abstract public function getBody();
}
