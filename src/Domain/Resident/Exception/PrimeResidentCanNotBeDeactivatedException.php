<?php

namespace App\Domain\Resident\Exception;

class PrimeResidentCanNotBeDeactivatedException extends \DomainException
{
    public function __construct()
    {
        parent::__construct(
            'Primary resident can be deactivated only when another active/pending primary resident exists.'
        );
    }
}
