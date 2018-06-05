<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Unit
 *
 * @ORM\Table(name="unit", uniqueConstraints={@ORM\UniqueConstraint(name="number_building", columns={"numberlowercase", "condobuilding_id"}), @ORM\UniqueConstraint(name="uniq_7c89a36dbf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_7c89a36d997f2d6f", columns={"condobuilding_id"}), @ORM\Index(name="idx_7c89a36de2b100ed", columns={"condo_id"})})
 * @ORM\Entity
 */
class Unit
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="unit_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=255, nullable=false)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="numberlowercase", type="string", length=255, nullable=false)
     */
    private $numberlowercase;

    /**
     * @var string
     *
     * @ORM\Column(name="phonenumber", type="string", length=255, nullable=false)
     */
    private $phonenumber;

    /**
     * @var string
     *
     * @ORM\Column(name="parkingspots", type="string", length=255, nullable=false)
     */
    private $parkingspots;

    /**
     * @var string
     *
     * @ORM\Column(name="bicyclespots", type="string", length=255, nullable=false)
     */
    private $bicyclespots;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var \Condobuilding
     *
     * @ORM\ManyToOne(targetEntity="Condobuilding")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="condobuilding_id", referencedColumnName="id")
     * })
     */
    private $condobuilding;

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

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getNumberlowercase(): ?string
    {
        return $this->numberlowercase;
    }

    public function setNumberlowercase(string $numberlowercase): self
    {
        $this->numberlowercase = $numberlowercase;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(string $phonenumber): self
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getParkingspots(): ?string
    {
        return $this->parkingspots;
    }

    public function setParkingspots(string $parkingspots): self
    {
        $this->parkingspots = $parkingspots;

        return $this;
    }

    public function getBicyclespots(): ?string
    {
        return $this->bicyclespots;
    }

    public function setBicyclespots(string $bicyclespots): self
    {
        $this->bicyclespots = $bicyclespots;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCondobuilding(): ?Condobuilding
    {
        return $this->condobuilding;
    }

    public function setCondobuilding(?Condobuilding $condobuilding): self
    {
        $this->condobuilding = $condobuilding;

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
