<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Platformendpoint
 *
 * @ORM\Table(name="platformendpoint")
 * @ORM\Entity
 */
class Platformendpoint
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="guid", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="platformendpoint_id_seq", allocationSize=1, initialValue=1)
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


}
