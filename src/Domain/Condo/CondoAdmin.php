<?php

namespace App\Domain\Condo;

use App\Domain\Account\Account;
use App\Domain\Common\Image;
use App\Domain\Condo\Event\CondoAdminCreatedEvent;
use App\Domain\Condo\Transformer\CondoAdminTransformer;
use App\Domain\DomainEventPublisher;
use App\Domain\DomainTransformer;
use App\Domain\User\Exception\UserEmailIsEmptyException;
use App\Domain\User\UserIdentity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class CondoAdmin extends UserIdentity
{
    /**
     * @ORM\ManyToMany(targetEntity="Condo", inversedBy="admins", cascade={"persist"})
     */
    private $condos;

    /**
     * @var Account|null
     * @ORM\ManyToOne(targetEntity="App\Domain\Account\Account", inversedBy="condoAdmins")
     */
    private $account;

    public function __construct(
        string $email,
        string $password,
        string $firstName,
        string $lastName,
        ?Image $image,
        string $phone
    ) {
        parent::__construct($email, $password, UserIdentity::ROLE_CONDO_ADMIN, $image, $firstName, $lastName, $phone);

        if (is_null($this->email)) {
            throw new UserEmailIsEmptyException();
        }

        $this->condos = new ArrayCollection();

        DomainEventPublisher::instance()->publish(
            new CondoAdminCreatedEvent(
                $this,
                $this->initialPasswordLink
            )
        );
    }

    public function getUserTransformer(): DomainTransformer
    {
        return new CondoAdminTransformer();
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    /**
     * @return Condo[]
     */
    public function getCondos(): Collection
    {
        return $this->condos->count() ? $this->condos->matching(
            Criteria::create()->orderBy(['name' => 'ASC'])
        ) : new ArrayCollection();
    }

    public function isAdminOfCondo(Condo $condo): bool
    {
        return $this->condos->contains($condo);
    }

    public function addCondo(Condo $condo): self
    {
        $this->condos[] = $condo;

        return $this;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function removeCondo(Condo $condo): self
    {
        $this->condos->removeElement($condo);

        return $this;
    }

    public function setAssignedCondos(array $assignedCondosIds): self
    {
        foreach ($this->getCondos() as $condo) {
            $condo->removeAdmin($this);
        }
        foreach ($assignedCondosIds as $assignedCondoId) {
            $this->getAccount()->getCondo($assignedCondoId)->addAdmin($this);
        }

        return $this;
    }
}
