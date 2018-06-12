<?php

namespace App\Service;

use App\AccessChecker\AccessChecker;
use App\Domain\User\UserIdentity;

abstract class AbstractHandler
{
    /**
     * @var UserIdentity|null
     */
    protected $currentUser;

    /**
     * @var AccessChecker
     */
    private $accessCheckerFunction;

    /** @var bool */
    private $isTransactionEnabled = true;

    public function disableTransaction(): self
    {
        $this->isTransactionEnabled = false;

        return $this;
    }

    protected function isTransactionEnabled(): bool
    {
        return $this->isTransactionEnabled;
    }

    public function execute(array $params, UserIdentity $currentUser = null, AccessChecker $accessChecker = null)
    {
        $this->currentUser = $currentUser;
        $this->accessCheckerFunction = $accessChecker;

        return ($this)(...$params);
    }

    protected function checkAccess(array $params)
    {
        if ($this->accessCheckerFunction) {
            if (!$this->currentUser) {
                throw new \LogicException('CurrentUser is not defined for accessCheckerFunction.');
            }
            $this->accessCheckerFunction->accessCheckerFunction()(...array_merge([$this->currentUser], $params));
        }
    }
}
