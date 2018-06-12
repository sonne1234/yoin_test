<?php

namespace App\Service;

use App\Domain\Resident\Resident;

class PushNotificator
{
    public function __construct()
    {
    }

    public function notifyResident(Resident $resident, string $message): void
    {
        // todo
//        $arn = $resident->getDeviceToken();
    }

    //todo
//    public function notifyResidentsByTopic(array $residents, string $message): void
}
