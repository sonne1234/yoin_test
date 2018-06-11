<?php

namespace App\Domain\Common\Exception;

class ImageNotFoundException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Image is not found.');
    }
}
