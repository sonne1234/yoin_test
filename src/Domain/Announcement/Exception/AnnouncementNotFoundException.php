<?php

namespace App\Domain\Announcement\Exception;

use Throwable;

class AnnouncementNotFoundException extends \DomainException
{
    public function __construct($message = 'Announcement is not found.', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
