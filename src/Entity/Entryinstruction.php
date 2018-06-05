<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entryinstruction
 *
 * @ORM\Table(name="entryinstruction", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_11f8acb6bf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_11f8acb63da5256d", columns={"image_id"}), @ORM\Index(name="idx_11f8acb68012c5b0", columns={"resident_id"}), @ORM\Index(name="idx_11f8acb6e2b100ed", columns={"condo_id"}), @ORM\Index(name="idx_11f8acb63174800f", columns={"createdby_id"})})
 * @ORM\Entity
 */
class Entryinstruction
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="entryinstruction_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="iscanceled", type="boolean", nullable=false)
     */
    private $iscanceled;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="cancelledat", type="datetimetz", nullable=true)
     */
    private $cancelledat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetimetz", nullable=false)
     */
    private $createdat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="periodstart", type="date", nullable=false)
     */
    private $periodstart;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="periodend", type="date", nullable=true)
     */
    private $periodend;

    /**
     * @var string
     *
     * @ORM\Column(name="visitorfirstname", type="string", length=255, nullable=false)
     */
    private $visitorfirstname;

    /**
     * @var string
     *
     * @ORM\Column(name="visitorlastname", type="string", length=255, nullable=false)
     */
    private $visitorlastname;

    /**
     * @var string
     *
     * @ORM\Column(name="visitorcompany", type="string", length=255, nullable=false)
     */
    private $visitorcompany;

    /**
     * @var string
     *
     * @ORM\Column(name="visitoremail", type="string", length=255, nullable=false)
     */
    private $visitoremail;

    /**
     * @var string
     *
     * @ORM\Column(name="visitoradditionalinfo", type="text", nullable=false)
     */
    private $visitoradditionalinfo;

    /**
     * @var bool
     *
     * @ORM\Column(name="iscancelledbyadmin", type="boolean", nullable=false)
     */
    private $iscancelledbyadmin;

    /**
     * @var bool
     *
     * @ORM\Column(name="issingleentry", type="boolean", nullable=false)
     */
    private $issingleentry;

    /**
     * @var \Useridentity
     *
     * @ORM\ManyToOne(targetEntity="Useridentity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="createdby_id", referencedColumnName="id")
     * })
     */
    private $createdby;

    /**
     * @var \Image
     *
     * @ORM\ManyToOne(targetEntity="Image")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     * })
     */
    private $image;

    /**
     * @var \Useridentity
     *
     * @ORM\ManyToOne(targetEntity="Useridentity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resident_id", referencedColumnName="id")
     * })
     */
    private $resident;

    /**
     * @var \Condo
     *
     * @ORM\ManyToOne(targetEntity="Condo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="condo_id", referencedColumnName="id")
     * })
     */
    private $condo;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIscanceled(): ?bool
    {
        return $this->iscanceled;
    }

    public function setIscanceled(bool $iscanceled): self
    {
        $this->iscanceled = $iscanceled;

        return $this;
    }

    public function getCancelledat(): ?\DateTimeInterface
    {
        return $this->cancelledat;
    }

    public function setCancelledat(?\DateTimeInterface $cancelledat): self
    {
        $this->cancelledat = $cancelledat;

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

    public function getPeriodstart(): ?\DateTimeInterface
    {
        return $this->periodstart;
    }

    public function setPeriodstart(\DateTimeInterface $periodstart): self
    {
        $this->periodstart = $periodstart;

        return $this;
    }

    public function getPeriodend(): ?\DateTimeInterface
    {
        return $this->periodend;
    }

    public function setPeriodend(?\DateTimeInterface $periodend): self
    {
        $this->periodend = $periodend;

        return $this;
    }

    public function getVisitorfirstname(): ?string
    {
        return $this->visitorfirstname;
    }

    public function setVisitorfirstname(string $visitorfirstname): self
    {
        $this->visitorfirstname = $visitorfirstname;

        return $this;
    }

    public function getVisitorlastname(): ?string
    {
        return $this->visitorlastname;
    }

    public function setVisitorlastname(string $visitorlastname): self
    {
        $this->visitorlastname = $visitorlastname;

        return $this;
    }

    public function getVisitorcompany(): ?string
    {
        return $this->visitorcompany;
    }

    public function setVisitorcompany(string $visitorcompany): self
    {
        $this->visitorcompany = $visitorcompany;

        return $this;
    }

    public function getVisitoremail(): ?string
    {
        return $this->visitoremail;
    }

    public function setVisitoremail(string $visitoremail): self
    {
        $this->visitoremail = $visitoremail;

        return $this;
    }

    public function getVisitoradditionalinfo(): ?string
    {
        return $this->visitoradditionalinfo;
    }

    public function setVisitoradditionalinfo(string $visitoradditionalinfo): self
    {
        $this->visitoradditionalinfo = $visitoradditionalinfo;

        return $this;
    }

    public function getIscancelledbyadmin(): ?bool
    {
        return $this->iscancelledbyadmin;
    }

    public function setIscancelledbyadmin(bool $iscancelledbyadmin): self
    {
        $this->iscancelledbyadmin = $iscancelledbyadmin;

        return $this;
    }

    public function getIssingleentry(): ?bool
    {
        return $this->issingleentry;
    }

    public function setIssingleentry(bool $issingleentry): self
    {
        $this->issingleentry = $issingleentry;

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

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getResident(): ?Useridentity
    {
        return $this->resident;
    }

    public function setResident(?Useridentity $resident): self
    {
        $this->resident = $resident;

        return $this;
    }

    public function getCondo(): ?Condo
    {
        return $this->condo;
    }

    public function setCondo(?Condo $condo): self
    {
        $this->condo = $condo;

        return $this;
    }


}
