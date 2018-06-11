<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Condo\Condo;
use App\Domain\ServiceRequest\ServiceRequest;
use App\Domain\Unit\Unit;
use App\Domain\User\UserIdentity;
use Doctrine\ORM\Query\ResultSetMapping;

class DoctrineServiceRequestRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return ServiceRequest::class;
    }

    public function getForUser(string $id, string $userId): ServiceRequest
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(ServiceRequest::class, 'sr');
        $rsm->addFieldResult('sr', 'id', 'id');
        $rsm->addFieldResult('sr', 'category', 'category');
        $rsm->addFieldResult('sr', 'title', 'title');
        $rsm->addFieldResult('sr', 'description', 'description');
        $rsm->addFieldResult('sr', 'createdat', 'createdAt');
        $rsm->addFieldResult('sr', 'updatedat', 'updatedAt');
        $rsm->addScalarResult('has_unread_comments', 'has_unread_comments', 'boolean');
        $rsm->addScalarResult('is_read', 'is_read', 'boolean');
        $rsm->addScalarResult('is_pinned', 'is_pinned', 'boolean');

        $sql = 'SELECT 
            sr.id, sr.category, sr.title,sr.description, sr.createdat, sr.updatedat,
              CASE 
              WHEN unread_comments IS NULL
                THEN FALSE 
              ELSE TRUE
              END 
              AS has_unread_comments,
              COALESCE(srs.isviewed, FALSE)  is_read,
              COALESCE(srs.ispinned, FALSE)  is_pinned
            FROM servicerequest sr
            LEFT JOIN(
            SELECT DISTINCT c.servicerequest_id AS id
                    FROM servicerequestcomment c
                    LEFT JOIN servicerequestcommentstate cs ON 
                        (
                          cs.servicerequestcomment_id = c.id
                          AND cs.user_id = :userid
                          AND cs.isviewed = TRUE
                        ) 
                    WHERE cs.id IS NULL
            ) unread_comments ON unread_comments.id = sr.id
            LEFT JOIN servicerequeststate srs ON  srs.servicerequest_id = sr.id AND srs.user_id = :userid
            WHERE sr.id = :servicerequestid
            ';
        $queryObject = $this->em->createNativeQuery($sql, $rsm);

        $queryObject->setParameter('userid', $userId);
        $queryObject->setParameter('servicerequestid', $id);

        $row = $queryObject->getSingleResult();
        /**
         * @var ServiceRequest
         */
        $entity = $row[0];
        $this->em->refresh($entity);

        $entity->setHasUnreadComments($row['has_unread_comments']);
        $entity->setIsRead($row['is_read']);
        $entity->setIsPinned($row['is_pinned']);

        return $entity;
    }

    public function markReadForCondo(Condo $condo, UserIdentity $userIdentity): void
    {
        $qb = $this->createQueryBuilder('sr')
            ->join('sr.resident', 'r')
            ->join(Unit::class, 'u', 'WITH', 'r.unit = u')
            ->where('u.condo = :condo')
            ->setParameter('condo', $condo);
        array_map(function ($serviceRequest) use ($userIdentity) {
            $serviceRequest->markRead($userIdentity);
        }, $qb->getQuery()->getResult());
    }
}
