<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscription
 *
 * @ORM\Table(name="subscription", indexes={@ORM\Index(name="idx_bbf7bf2bbc8cf43a", columns={"platformendpoint_id"}), @ORM\Index(name="idx_bbf7bf2b1f55203d", columns={"topic_id"})})
 * @ORM\Entity
 */
class Subscription
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="guid", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="subscription_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="arn", type="string", length=255, nullable=false)
     */
    private $arn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime", nullable=false)
     */
    private $createdat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedat", type="datetime", nullable=false)
     */
    private $updatedat;

    /**
     * @var \Topic
     *
     * @ORM\ManyToOne(targetEntity="Topic")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="topic_id", referencedColumnName="id")
     * })
     */
    private $topic;

    /**
     * @var \Platformendpoint
     *
     * @ORM\ManyToOne(targetEntity="Platformendpoint")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="platformendpoint_id", referencedColumnName="id")
     * })
     */
    private $platformendpoint;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    public function setArn(string $arn): self
    {
        $this->arn = $arn;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(\DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getUpdatedat(): ?\DateTimeInterface
    {
        return $this->updatedat;
    }

    public function setUpdatedat(\DateTimeInterface $updatedat): self
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getPlatformendpoint(): ?Platformendpoint
    {
        return $this->platformendpoint;
    }

    public function setPlatformendpoint(?Platformendpoint $platformendpoint): self
    {
        $this->platformendpoint = $platformendpoint;

        return $this;
    }


}
