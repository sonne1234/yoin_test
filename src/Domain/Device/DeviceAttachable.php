<?php

namespace App\Domain\Device;

interface DeviceAttachable
{
    public function attachDevice(Device $device);

    public function detachDevice(Device $device);

    /** @var Device[] */
    public function getDevices();

    public function hasDevice(Device $device): bool;
}
