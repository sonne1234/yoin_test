<?php

namespace App\Service;

use App\AccessChecker\AccessChecker;
use App\Request\HttpRequest;
use App\Domain\User\UserIdentity;

abstract class AbstractCommand
{
    /** @var HttpRequest */
    protected $request;
    /** @var UserIdentity */
    protected $currentUser;
    /** @var AccessChecker */
    protected $accessChecker;

    protected $result;

    /**
     * EditCondoGeneralDataCommand constructor.
     *
     * @param $params
     * @param UserIdentity  $currentUser
     * @param AccessChecker $accessChecker
     */
    public function __construct(HttpRequest $request = null, UserIdentity $currentUser = null, AccessChecker $accessChecker = null)
    {
        $this->request = $request;
        $this->currentUser = $currentUser;
        $this->accessChecker = $accessChecker;
    }

    /**
     * @return HttpRequest
     */
    public function getRequest(): ?HttpRequest
    {
        return $this->request;
    }

    /**
     * @return UserIdentity
     */
    public function getCurrentUser(): ?UserIdentity
    {
        return $this->currentUser;
    }

    /**
     * @return AccessChecker
     */
    public function getAccessChecker(): ?AccessChecker
    {
        return $this->accessChecker;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     *
     * @return AbstractCommand
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }
}
