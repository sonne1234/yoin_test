<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\DomainCriteria;
use App\Domain\DomainRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractDoctrineRepository implements DomainRepository
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;

    public function AbstractDoctrineRepository(EntityManager $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository($this->repositoryClassName());
    }

    abstract protected function repositoryClassName(): string;

    public function get(string $id)
    {
        return $this->repo->find($id);
    }

    public function getWithWriteLock(string $id)
    {
        return $this->em->find(
            $this->repositoryClassName(),
            $id,
            LockMode::PESSIMISTIC_WRITE
        );
    }

    public function add($entity): void
    {
        $this->em->persist($entity);
    }

    public function remove($entity): void
    {
        $this->em->remove($entity);
    }

    public function getCollectionByCriteria(DomainCriteria $criteria): Collection
    {
        return $this->repo->matching($criteria->create());
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
        return $this->repo->createQueryBuilder($alias);
    }

    public function getAll(): iterable
    {
        return $this->repo->findAll();
    }

    public function getEm(): ?EntityManagerInterface
    {
        return $this->em;
    }

    protected function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->em->getConnection()->prepare($sql);
        foreach ($params as $name => $val) {
            $stmt->bindValue($name, $val);
        }
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
