<?php

namespace App\Domain\PlatformNotification\Handler;

use App\Domain\PlatformNotification\AbstractNotification;
use App\Domain\PlatformNotification\Command\MarkAllAsReadCommand;
use App\Infrastructure\Persistence\Doctrine\DoctrinePlatformNotificationRepository;

class MarkAllAsReadHandler
{
    /** @var DoctrinePlatformNotificationRepository */
    private $notificationRepository;

    /**
     * MarkAllAsReadHandler constructor.
     *
     * @param DoctrinePlatformNotificationRepository $notificationRepository
     */
    public function __construct(DoctrinePlatformNotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function handle(MarkAllAsReadCommand $command)
    {
        $user = $command->getUser();
        $recipientNotifications = $this->notificationRepository->getUserUnreadNotificationQuery($user)->getQuery()->execute();

        /** @var AbstractNotification $notification */
        foreach ($recipientNotifications as $notification) {
            $notification->markAsReadBy($user);
        }
    }
}
