<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entryinstructionlog
 *
 * @ORM\Table(name="entryinstructionlog", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_c775255dbf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_c775255d30c2b85c", columns={"entryinstruction_id"}), @ORM\Index(name="idx_c775255d3174800f", columns={"createdby_id"})})
 * @ORM\Entity
 */
class Entryinstructionlog
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="entryinstructionlog_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="arriveat", type="datetimetz", nullable=true)
     */
    private $arriveat;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="exitat", type="datetimetz", nullable=true)
     */
    private $exitat;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="createdat", type="datetimetz", nullable=true)
     */
    private $createdat;

    /**
     * @var \Entryinstruction
     *
     * @ORM\ManyToOne(targetEntity="Entryinstruction")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entryinstruction_id", referencedColumnName="id")
     * })
     */
    private $entryinstruction;

    /**
     * @var \Useridentity
     *
     * @ORM\ManyToOne(targetEntity="Useridentity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="createdby_id", referencedColumnName="id")
     * })
     */
    private $createdby;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getArriveat(): ?\DateTimeInterface
    {
        return $this->arriveat;
    }

    public function setArriveat(?\DateTimeInterface $arriveat): self
    {
        $this->arriveat = $arriveat;

        return $this;
    }

    public function getExitat(): ?\DateTimeInterface
    {
        return $this->exitat;
    }

    public function setExitat(?\DateTimeInterface $exitat): self
    {
        $this->exitat = $exitat;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(?\DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getEntryinstruction(): ?Entryinstruction
    {
        return $this->entryinstruction;
    }

    public function setEntryinstruction(?Entryinstruction $entryinstruction): self
    {
        $this->entryinstruction = $entryinstruction;

        return $this;
    }

    public function getCreatedby(): ?Useridentity
    {
        return $this->createdby;
    }

    public function setCreatedby(?Useridentity $createdby): self
    {
        $this->createdby = $createdby;

        return $this;
    }


}
