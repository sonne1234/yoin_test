<?php

namespace App\Infrastructure\Persistence\InMemory;

use App\Domain\DomainCriteria;
use App\Domain\DomainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class InMemoryRepository implements DomainRepository
{
    /**
     * @var ArrayCollection
     */
    private $entities;

    public function __construct()
    {
        $this->entities = new ArrayCollection();
    }

    public function get(string $id)
    {
        $res = $this
            ->entities
            ->matching(Criteria::create()->where(Criteria::expr()->eq('id', $id)));

        return $res->count()
            ? $res->first()
            : null;
    }

    public function getWithWriteLock(string $id)
    {
        return $this->get($id);
    }

    public function add($entity): void
    {
        $this->entities->add($entity);
    }

    public function remove($entity): void
    {
        $this->entities->removeElement($entity);
    }

    public function getCollectionByCriteria(DomainCriteria $criteria): Collection
    {
        return $this->entities->matching($criteria->create());
    }

    public function getOneByCriteria(DomainCriteria $criteria)
    {
        $res = $this->getCollectionByCriteria($criteria);

        return $res->count()
            ? $res->first()
            : null;
    }

    public function createQueryBuilder(string $alias = ''): QueryBuilder
    {
        throw new \LogicException('Not implemented');
    }

    public function getAll(): iterable
    {
        return $this->entities->getValues();
    }

    public function getEm(): ?EntityManagerInterface
    {
        return null;
    }
}
