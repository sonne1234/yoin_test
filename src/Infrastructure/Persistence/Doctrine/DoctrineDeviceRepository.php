<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Device\Device;
use App\Domain\Device\DeviceRepository;

class DoctrineDeviceRepository extends AbstractDoctrineRepository implements DeviceRepository
{
    protected function repositoryClassName(): string
    {
        return Device::class;
    }
}
