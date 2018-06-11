<?php

namespace App\Domain\PlatformNotification\Handler;

use App\Domain\Condo\CondoAdminRepository;
use App\Domain\DomainRepository;
use App\Domain\PlatformNotification\AbstractNotification;
use App\Domain\PlatformNotification\Command\RegisterNotificationCommand;
use App\Domain\User\UserIdentity;
use App\Infrastructure\Client\Centrifugo\PlatformNotificationMessage;
use App\Infrastructure\Client\CentrifugoClient;
use App\Infrastructure\Persistence\Doctrine\DoctrineAccountAdminRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineCondoAdminRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrinePlatformAdminRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrinePlatformNotificationRepository;

class RegisterNotificationHandler
{
    /** @var DoctrinePlatformAdminRepository */
    private $platformAdminRepo;

    /** @var DoctrineAccountAdminRepository */
    private $accountAdminRepo;

    /** @var CondoAdminRepository */
    private $condoAdminRepo;

    /** @var DoctrinePlatformNotificationRepository */
    private $notificationRepository;

    /** @var CentrifugoClient */
    private $centrifugoClient;

    /** @var array */
    private $userRepos;

    /**
     * RegisterNotificationHandler constructor.
     *
     * @param DoctrinePlatformAdminRepository        $platformAdminRepo
     * @param DoctrineAccountAdminRepository         $accountAdminRepo
     * @param DoctrineCondoAdminRepository           $condoAdminRepo
     * @param DoctrinePlatformNotificationRepository $notificationRepository
     * @param CentrifugoClient                       $centrifugoClient
     */
    public function __construct(
        DoctrinePlatformAdminRepository $platformAdminRepo,
        DoctrineAccountAdminRepository $accountAdminRepo,
        DoctrineCondoAdminRepository $condoAdminRepo,
        DoctrinePlatformNotificationRepository $notificationRepository,
        CentrifugoClient $centrifugoClient
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->centrifugoClient = $centrifugoClient;
        $this->userRepos = [
            DoctrinePlatformAdminRepository::class => $platformAdminRepo,
            DoctrineAccountAdminRepository::class => $accountAdminRepo,
            DoctrineCondoAdminRepository::class => $condoAdminRepo,
        ];
    }

    public function handle(RegisterNotificationCommand $command)
    {
        $notification = $command->getNotification();
        $this->notificationRepository->add($notification);

        $recipients = $this->getRecipients($notification);

        /** @var UserIdentity $recipient */
        foreach ($recipients as $recipient) {
            if ($notification->getAuthor() && $notification->getAuthor()->getId() == $recipient->getId()) {
                continue;
            }
            $notification->addRecipient($recipient);
            $this->broadCastNotification($notification, $recipient);
        }
        /** @var DoctrinePlatformNotificationRepository $notificationRepo */
        $notificationRepo = $this->userRepos[DoctrinePlatformAdminRepository::class];
        $notificationRepo->add($notification);
    }

    private function broadCastNotification(AbstractNotification $notification, UserIdentity $recipient)
    {
        $message = new PlatformNotificationMessage($recipient->getId(), $notification->getCondoId());
        $this->centrifugoClient->publishMessage($message);
    }

    private function getRecipients(AbstractNotification $notification)
    {
        $recipientsCriteria = $notification->getRecipientsCriteria();
        /** @var DomainRepository $repo */
        $repo = $this->userRepos[$notification->getRecipientsRepoClass()];
        $items = $repo->getCollectionByCriteria(
            $recipientsCriteria
        );
        if ($notification->getRecipientFilter()) {
            return $items->filter($notification->getRecipientFilter());
        }

        return $items;
    }
}
