<?php

namespace App\Domain\Device\Event;

use App\Domain\Device\Device;

class DeviceCreatedEvent
{
    /** @var Device */
    private $device;

    /**
     * DeviceCreatedEvent constructor.
     *
     * @param Device $device
     */
    public function __construct(Device $device)
    {
        $this->device = $device;
    }

    /**
     * @return Device
     */
    public function getDevice(): Device
    {
        return $this->device;
    }
}
