<?php

namespace App\Domain\User\Criteria;

use Doctrine\Common\Collections\Criteria;

class UserByResetPasswordLinkCriteria extends UserByInitialPasswordLinkCriteria
{
    public function create(): Criteria
    {
        return Criteria::create()->where(
            Criteria::expr()->eq('resetPasswordLink', $this->link)
        );
    }
}
