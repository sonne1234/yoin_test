<?php

namespace App\Application\Service;

use App\Application\AccessChecker\AccessChecker;
use App\Domain\User\UserIdentity;

abstract class AbstractCommandHandler
{
    /**
     * @var UserIdentity|null
     */
    protected $currentUser;

    /**
     * @var AccessChecker
     */
    private $accessCheckerFunction;

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

    abstract protected function process(AbstractCommand $command);

    public function handle(AbstractCommand $command)
    {
        $this->currentUser = $command->getCurrentUser();
        $this->accessCheckerFunction = $command->getAccessChecker();
        $result = $this->process($command);
        $command->setResult($result);
    }
}
