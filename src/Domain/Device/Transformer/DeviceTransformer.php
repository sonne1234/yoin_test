<?php

namespace App\Domain\Device\Transformer;

use App\Domain\Device\Device;
use App\Domain\DomainTransformer;

class DeviceTransformer extends DomainTransformer
{
    /**
     * @param Device $device
     *
     * @return array
     */
    protected function transformOneEntity($device): array
    {
        return [
            'id' => $device->getId(),
            'platform' => $device->getPlatform(),
        ];
    }
}
