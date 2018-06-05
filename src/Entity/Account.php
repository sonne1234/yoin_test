<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Account
 *
 * @ORM\Table(name="account", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_b28b6f383da5256d", columns={"image_id"}), @ORM\UniqueConstraint(name="uniq_b28b6f38bf396750", columns={"id"})})
 * @ORM\Entity
 */
class Account
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="account_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime", nullable=false)
     */
    private $createdat;

    /**
     * @var bool
     *
     * @ORM\Column(name="isaccountinfofilled", type="boolean", nullable=false)
     */
    private $isaccountinfofilled;

    /**
     * @var string
     *
     * @ORM\Column(name="billingdata_companyname", type="text", nullable=false)
     */
    private $billingdataCompanyname;

    /**
     * @var string
     *
     * @ORM\Column(name="billingdata_streetname", type="string", length=255, nullable=false)
     */
    private $billingdataStreetname;

    /**
     * @var string
     *
     * @ORM\Column(name="billingdata_buildingnumber", type="string", length=255, nullable=false)
     */
    private $billingdataBuildingnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="billingdata_neighborhoodname", type="string", length=255, nullable=false)
     */
    private $billingdataNeighborhoodname;

    /**
     * @var string
     *
     * @ORM\Column(name="billingdata_zipcode", type="string", length=255, nullable=false)
     */
    private $billingdataZipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="billingdata_city", type="string", length=255, nullable=false)
     */
    private $billingdataCity;

    /**
     * @var string
     *
     * @ORM\Column(name="billingdata_state", type="string", length=255, nullable=false)
     */
    private $billingdataState;

    /**
     * @var string
     *
     * @ORM\Column(name="billingdata_country", type="string", length=255, nullable=false)
     */
    private $billingdataCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="billingdata_taxidnumber", type="string", length=255, nullable=false)
     */
    private $billingdataTaxidnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_companyname", type="text", nullable=false)
     */
    private $generaldataCompanyname;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_streetname", type="string", length=255, nullable=false)
     */
    private $generaldataStreetname;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_buildingnumber", type="string", length=255, nullable=false)
     */
    private $generaldataBuildingnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_neighborhoodname", type="string", length=255, nullable=false)
     */
    private $generaldataNeighborhoodname;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_zipcode", type="string", length=255, nullable=false)
     */
    private $generaldataZipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_city", type="string", length=255, nullable=false)
     */
    private $generaldataCity;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_state", type="string", length=255, nullable=false)
     */
    private $generaldataState;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_country", type="string", length=255, nullable=false)
     */
    private $generaldataCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_phone", type="string", length=255, nullable=false)
     */
    private $generaldataPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_altphone", type="string", length=255, nullable=false)
     */
    private $generaldataAltphone;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_email", type="string", length=255, nullable=false)
     */
    private $generaldataEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_contactfirstname", type="string", length=255, nullable=false)
     */
    private $generaldataContactfirstname;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_contactlastname", type="string", length=255, nullable=false)
     */
    private $generaldataContactlastname;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_contactemail", type="string", length=255, nullable=false)
     */
    private $generaldataContactemail;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_contactphone", type="string", length=255, nullable=false)
     */
    private $generaldataContactphone;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_contactaltphone", type="string", length=255, nullable=false)
     */
    private $generaldataContactaltphone;

    /**
     * @var \Image
     *
     * @ORM\ManyToOne(targetEntity="Image")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     * })
     */
    private $image;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getIsaccountinfofilled(): ?bool
    {
        return $this->isaccountinfofilled;
    }

    public function setIsaccountinfofilled(bool $isaccountinfofilled): self
    {
        $this->isaccountinfofilled = $isaccountinfofilled;

        return $this;
    }

    public function getBillingdataCompanyname(): ?string
    {
        return $this->billingdataCompanyname;
    }

    public function setBillingdataCompanyname(string $billingdataCompanyname): self
    {
        $this->billingdataCompanyname = $billingdataCompanyname;

        return $this;
    }

    public function getBillingdataStreetname(): ?string
    {
        return $this->billingdataStreetname;
    }

    public function setBillingdataStreetname(string $billingdataStreetname): self
    {
        $this->billingdataStreetname = $billingdataStreetname;

        return $this;
    }

    public function getBillingdataBuildingnumber(): ?string
    {
        return $this->billingdataBuildingnumber;
    }

    public function setBillingdataBuildingnumber(string $billingdataBuildingnumber): self
    {
        $this->billingdataBuildingnumber = $billingdataBuildingnumber;

        return $this;
    }

    public function getBillingdataNeighborhoodname(): ?string
    {
        return $this->billingdataNeighborhoodname;
    }

    public function setBillingdataNeighborhoodname(string $billingdataNeighborhoodname): self
    {
        $this->billingdataNeighborhoodname = $billingdataNeighborhoodname;

        return $this;
    }

    public function getBillingdataZipcode(): ?string
    {
        return $this->billingdataZipcode;
    }

    public function setBillingdataZipcode(string $billingdataZipcode): self
    {
        $this->billingdataZipcode = $billingdataZipcode;

        return $this;
    }

    public function getBillingdataCity(): ?string
    {
        return $this->billingdataCity;
    }

    public function setBillingdataCity(string $billingdataCity): self
    {
        $this->billingdataCity = $billingdataCity;

        return $this;
    }

    public function getBillingdataState(): ?string
    {
        return $this->billingdataState;
    }

    public function setBillingdataState(string $billingdataState): self
    {
        $this->billingdataState = $billingdataState;

        return $this;
    }

    public function getBillingdataCountry(): ?string
    {
        return $this->billingdataCountry;
    }

    public function setBillingdataCountry(string $billingdataCountry): self
    {
        $this->billingdataCountry = $billingdataCountry;

        return $this;
    }

    public function getBillingdataTaxidnumber(): ?string
    {
        return $this->billingdataTaxidnumber;
    }

    public function setBillingdataTaxidnumber(string $billingdataTaxidnumber): self
    {
        $this->billingdataTaxidnumber = $billingdataTaxidnumber;

        return $this;
    }

    public function getGeneraldataCompanyname(): ?string
    {
        return $this->generaldataCompanyname;
    }

    public function setGeneraldataCompanyname(string $generaldataCompanyname): self
    {
        $this->generaldataCompanyname = $generaldataCompanyname;

        return $this;
    }

    public function getGeneraldataStreetname(): ?string
    {
        return $this->generaldataStreetname;
    }

    public function setGeneraldataStreetname(string $generaldataStreetname): self
    {
        $this->generaldataStreetname = $generaldataStreetname;

        return $this;
    }

    public function getGeneraldataBuildingnumber(): ?string
    {
        return $this->generaldataBuildingnumber;
    }

    public function setGeneraldataBuildingnumber(string $generaldataBuildingnumber): self
    {
        $this->generaldataBuildingnumber = $generaldataBuildingnumber;

        return $this;
    }

    public function getGeneraldataNeighborhoodname(): ?string
    {
        return $this->generaldataNeighborhoodname;
    }

    public function setGeneraldataNeighborhoodname(string $generaldataNeighborhoodname): self
    {
        $this->generaldataNeighborhoodname = $generaldataNeighborhoodname;

        return $this;
    }

    public function getGeneraldataZipcode(): ?string
    {
        return $this->generaldataZipcode;
    }

    public function setGeneraldataZipcode(string $generaldataZipcode): self
    {
        $this->generaldataZipcode = $generaldataZipcode;

        return $this;
    }

    public function getGeneraldataCity(): ?string
    {
        return $this->generaldataCity;
    }

    public function setGeneraldataCity(string $generaldataCity): self
    {
        $this->generaldataCity = $generaldataCity;

        return $this;
    }

    public function getGeneraldataState(): ?string
    {
        return $this->generaldataState;
    }

    public function setGeneraldataState(string $generaldataState): self
    {
        $this->generaldataState = $generaldataState;

        return $this;
    }

    public function getGeneraldataCountry(): ?string
    {
        return $this->generaldataCountry;
    }

    public function setGeneraldataCountry(string $generaldataCountry): self
    {
        $this->generaldataCountry = $generaldataCountry;

        return $this;
    }

    public function getGeneraldataPhone(): ?string
    {
        return $this->generaldataPhone;
    }

    public function setGeneraldataPhone(string $generaldataPhone): self
    {
        $this->generaldataPhone = $generaldataPhone;

        return $this;
    }

    public function getGeneraldataAltphone(): ?string
    {
        return $this->generaldataAltphone;
    }

    public function setGeneraldataAltphone(string $generaldataAltphone): self
    {
        $this->generaldataAltphone = $generaldataAltphone;

        return $this;
    }

    public function getGeneraldataEmail(): ?string
    {
        return $this->generaldataEmail;
    }

    public function setGeneraldataEmail(string $generaldataEmail): self
    {
        $this->generaldataEmail = $generaldataEmail;

        return $this;
    }

    public function getGeneraldataContactfirstname(): ?string
    {
        return $this->generaldataContactfirstname;
    }

    public function setGeneraldataContactfirstname(string $generaldataContactfirstname): self
    {
        $this->generaldataContactfirstname = $generaldataContactfirstname;

        return $this;
    }

    public function getGeneraldataContactlastname(): ?string
    {
        return $this->generaldataContactlastname;
    }

    public function setGeneraldataContactlastname(string $generaldataContactlastname): self
    {
        $this->generaldataContactlastname = $generaldataContactlastname;

        return $this;
    }

    public function getGeneraldataContactemail(): ?string
    {
        return $this->generaldataContactemail;
    }

    public function setGeneraldataContactemail(string $generaldataContactemail): self
    {
        $this->generaldataContactemail = $generaldataContactemail;

        return $this;
    }

    public function getGeneraldataContactphone(): ?string
    {
        return $this->generaldataContactphone;
    }

    public function setGeneraldataContactphone(string $generaldataContactphone): self
    {
        $this->generaldataContactphone = $generaldataContactphone;

        return $this;
    }

    public function getGeneraldataContactaltphone(): ?string
    {
        return $this->generaldataContactaltphone;
    }

    public function setGeneraldataContactaltphone(string $generaldataContactaltphone): self
    {
        $this->generaldataContactaltphone = $generaldataContactaltphone;

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


}
