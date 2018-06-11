<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\PlatformNotification\AbstractNotification;
use App\Domain\User\UserIdentity;
use Doctrine\ORM\QueryBuilder;

class DoctrinePlatformNotificationRepository extends AbstractDoctrineRepository
{
    public const ALIAS = 'pn';

    protected function repositoryClassName(): string
    {
        return AbstractNotification::class;
    }

    public function getUserUnreadNotificationQuery(UserIdentity $user): QueryBuilder
    {
        $qb = $this->createQueryBuilder(self::ALIAS);
        $qb->join(self::ALIAS.'.notificationRecipients', 'pnr')
            ->andWhere($qb->expr()->eq('pnr.user', ':user'))
            ->andWhere($qb->expr()->isNull('pnr.readAt'))
            ->setParameter('user', $user);

        return $qb;
    }
}
