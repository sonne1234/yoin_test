<?php

namespace App\Domain;

trait ToArrayTransformTrait
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
