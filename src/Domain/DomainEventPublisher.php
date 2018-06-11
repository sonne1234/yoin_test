<?php

namespace App\Domain;

use App\Application\EventListener\EventQueuedListener;
use App\Domain\User\UserIdentity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class DomainEventPublisher implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    const SUBSCRIBER_TYPE_INSTANT = 'instant';
    const SUBSCRIBER_TYPE_QUEUED = 'queued';

    /**
     * @var ArrayCollection|DomainEventListener[]
     */
    private $subscribers;

    /**
     * @var DomainEvent[]
     */
    private $events = [];

    /**
     * @var self
     */
    private static $instance = null;

    /**
     * @var bool
     */
    private $isDispatchDisabled = false;

    /**
     * @var bool
     */
    private $isPublishDisabled = false;

    /** @var UserIdentity|null */
    private $currentUser;

    public static function instance(): self
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
        $this->subscribers = new ArrayCollection();
    }

    private function __clone()
    {
    }

    public function subscribe(DomainEventListener $subscriber): void
    {
        if (!$this->subscribers->contains($subscriber)) {
            $this->subscribers->add($subscriber);
        }
    }

    public function publish(DomainEvent $event): void
    {
        if ($this->isPublishDisabled) {
            return;
        }

        $this->events[] = $event->setCurrentUser($this->currentUser);
    }

    public function dispatchAllEvents(string $subscriberType = ''): void
    {
        if ($this->isDispatchDisabled) {
            return;
        }

        //        $this->isDispatchDisabled = true;

        foreach ($this->events as $key => $event) {
            foreach ($this->subscribers as $subscriber) {
                if ($subscriber->isSubscribedTo($event)) {
                    if ($subscriber instanceof EventQueuedListener &&
                        (!$subscriberType || $subscriberType === self::SUBSCRIBER_TYPE_QUEUED)
                    ) {
                        // add to queue
                        $subscriber->addToQueue($event);
                    } elseif (!$subscriber instanceof EventQueuedListener &&
                        (!$subscriberType || $subscriberType === self::SUBSCRIBER_TYPE_INSTANT)
                    ) {
                        // handle immediately
                        $subscriber->handle($event);
                    }
                }
            }
            //            unset($this->events[$key]);
        }

        //        $this->isDispatchDisabled = false;
    }

    public function clearAll()
    {
        $this->clearSubscribers();
        $this->clearEvents();
    }

    public function clearEvents()
    {
        $this->events = [];
    }

    public function clearSubscribers()
    {
        $this->subscribers->clear();
    }

    public function setCurrentUser(?UserIdentity $userIdentity)
    {
        $this->currentUser = $userIdentity;
    }

    public function disablePublish(): void
    {
        $this->isPublishDisabled = true;
    }

    public function enablePublish(): void
    {
        $this->isPublishDisabled = false;
    }
}
