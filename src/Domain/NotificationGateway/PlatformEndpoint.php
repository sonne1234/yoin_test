<?php

namespace App\Domain\NotificationGateway;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 */
class PlatformEndpoint
{
    use TimestampableEntity;

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $arn;

    /**
     * @var ArrayCollection Subscription[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\NotificationGateway\Subscription",
     *     mappedBy="platformEndpoint",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *   )
     */
    private $subscriptions;

    /**
     * PlatformEndpoint constructor.
     *
     * @param Subscription[] $subscriptions
     */
    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return PlatformEndpoint
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getArn(): string
    {
        return $this->arn;
    }

    /**
     * @param string $arn
     *
     * @return PlatformEndpoint
     */
    public function setArn(string $arn): self
    {
        $this->arn = $arn;

        return $this;
    }

    public function hasSubscriptionForTopic(Topic $topic): bool
    {
        foreach ($this->subscriptions as $subscription) {
            if ($subscription->getTopic()->getId() === $topic->getId()) {
                return true;
            }
        }

        return false;
    }

    public function addSubscription(Subscription $subscription): self
    {
        $this->subscriptions->add($subscription->setPlatformEndpoint($this));

        return $this;
    }

    public function removeSubscription(Subscription $subscription)
    {
        $this->subscriptions->removeElement($subscription);
    }

    /**
     * @return ArrayCollection Subscription[]
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param ArrayCollection Subscription[]
     *
     * @return PlatformEndpoint
     */
    public function setSubscriptions(ArrayCollection $subscriptions): self
    {
        $this->subscriptions = $subscriptions;

        return $this;
    }
}
