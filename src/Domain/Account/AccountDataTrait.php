<?php

namespace App\Domain\Account;

trait AccountDataTrait
{
    public function isDataFilled(): bool
    {
        foreach (self::REQUIRED_FIELDS_FOR_VERIFIED_ACCOUNT as $field) {
            if ($this->$field === '') {
                return false;
            }
        }

        return true;
    }
}
