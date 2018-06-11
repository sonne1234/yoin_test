<?php

namespace App\Domain\Resident\Exception;

class ResidentCanNotBeRemovedException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('You can delete residents only with status isn\'t created or in pending.');
    }
}
