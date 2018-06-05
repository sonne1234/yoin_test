<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Condo
 *
 * @ORM\Table(name="condo", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_24e106913da5256d", columns={"image_id"}), @ORM\UniqueConstraint(name="uniq_24e10691bf396750", columns={"id"})}, indexes={@ORM\Index(name="idx_24e106919b6b5fba", columns={"account_id"})})
 * @ORM\Entity
 */
class Condo
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="condo_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetimetz", nullable=false)
     */
    private $createdat;

    /**
     * @var string
     *
     * @ORM\Column(name="paymentaccountid", type="string", length=255, nullable=false)
     */
    private $paymentaccountid;

    /**
     * @var bool
     *
     * @ORM\Column(name="ispaymentactive", type="boolean", nullable=false)
     */
    private $ispaymentactive;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_name", type="string", length=255, nullable=false)
     */
    private $generaldataName;

    /**
     * @var string
     *
     * @ORM\Column(name="generaldata_streetname", type="string", length=255, nullable=false)
     */
    private $generaldataStreetname;

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
     * @ORM\Column(name="generaldata_description", type="text", nullable=false)
     */
    private $generaldataDescription;

    /**
     * @var array
     *
     * @ORM\Column(name="generaldata_customfields", type="array", nullable=false)
     */
    private $generaldataCustomfields;

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
     * @var array
     *
     * @ORM\Column(name="billingdata_customfields", type="array", nullable=false)
     */
    private $billingdataCustomfields;

    /**
     * @var string
     *
     * @ORM\Column(name="whitelabeldata_colorscheme", type="string", length=255, nullable=false)
     */
    private $whitelabeldataColorscheme;

    /**
     * @var int|null
     *
     * @ORM\Column(name="maintenancedata_maintenancefeesize", type="integer", nullable=true)
     */
    private $maintenancedataMaintenancefeesize;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="maintenancedata_nextinvoicedate", type="datetime", nullable=true)
     */
    private $maintenancedataNextinvoicedate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="paymentdata_type", type="integer", nullable=true)
     */
    private $paymentdataType;

    /**
     * @var int|null
     *
     * @ORM\Column(name="paymentdata_status", type="integer", nullable=true)
     */
    private $paymentdataStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="paymentdata_bankid", type="string", length=255, nullable=true)
     */
    private $paymentdataBankid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="paymentdata_businessownername", type="string", length=255, nullable=true)
     */
    private $paymentdataBusinessownername;

    /**
     * @var string|null
     *
     * @ORM\Column(name="paymentdata_affiliationnumber", type="string", length=255, nullable=true)
     */
    private $paymentdataAffiliationnumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="paymentdata_clabe", type="string", length=255, nullable=true)
     */
    private $paymentdataClabe;

    /**
     * @var string|null
     *
     * @ORM\Column(name="paymentdata_accountnumber", type="string", length=255, nullable=true)
     */
    private $paymentdataAccountnumber;

    /**
     * @var float|null
     *
     * @ORM\Column(name="paymentdata_vmcbankfee", type="float", precision=10, scale=0, nullable=true)
     */
    private $paymentdataVmcbankfee;

    /**
     * @var float|null
     *
     * @ORM\Column(name="paymentdata_vmcplatformfee", type="float", precision=10, scale=0, nullable=true)
     */
    private $paymentdataVmcplatformfee;

    /**
     * @var float|null
     *
     * @ORM\Column(name="paymentdata_amexbankfee", type="float", precision=10, scale=0, nullable=true)
     */
    private $paymentdataAmexbankfee;

    /**
     * @var float|null
     *
     * @ORM\Column(name="paymentdata_amexplatformfee", type="float", precision=10, scale=0, nullable=true)
     */
    private $paymentdataAmexplatformfee;

    /**
     * @var string|null
     *
     * @ORM\Column(name="paymentdata_errorstatus", type="string", length=255, nullable=true)
     */
    private $paymentdataErrorstatus;

    /**
     * @var \Account
     *
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     * })
     */
    private $account;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Useridentity", mappedBy="condo")
     */
    private $condoadmin;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->condoadmin = new \Doctrine\Common\Collections\ArrayCollection();
    }

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

    public function getPaymentaccountid(): ?string
    {
        return $this->paymentaccountid;
    }

    public function setPaymentaccountid(string $paymentaccountid): self
    {
        $this->paymentaccountid = $paymentaccountid;

        return $this;
    }

    public function getIspaymentactive(): ?bool
    {
        return $this->ispaymentactive;
    }

    public function setIspaymentactive(bool $ispaymentactive): self
    {
        $this->ispaymentactive = $ispaymentactive;

        return $this;
    }

    public function getGeneraldataName(): ?string
    {
        return $this->generaldataName;
    }

    public function setGeneraldataName(string $generaldataName): self
    {
        $this->generaldataName = $generaldataName;

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

    public function getGeneraldataDescription(): ?string
    {
        return $this->generaldataDescription;
    }

    public function setGeneraldataDescription(string $generaldataDescription): self
    {
        $this->generaldataDescription = $generaldataDescription;

        return $this;
    }

    public function getGeneraldataCustomfields(): ?array
    {
        return $this->generaldataCustomfields;
    }

    public function setGeneraldataCustomfields(array $generaldataCustomfields): self
    {
        $this->generaldataCustomfields = $generaldataCustomfields;

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

    public function getBillingdataCustomfields(): ?array
    {
        return $this->billingdataCustomfields;
    }

    public function setBillingdataCustomfields(array $billingdataCustomfields): self
    {
        $this->billingdataCustomfields = $billingdataCustomfields;

        return $this;
    }

    public function getWhitelabeldataColorscheme(): ?string
    {
        return $this->whitelabeldataColorscheme;
    }

    public function setWhitelabeldataColorscheme(string $whitelabeldataColorscheme): self
    {
        $this->whitelabeldataColorscheme = $whitelabeldataColorscheme;

        return $this;
    }

    public function getMaintenancedataMaintenancefeesize(): ?int
    {
        return $this->maintenancedataMaintenancefeesize;
    }

    public function setMaintenancedataMaintenancefeesize(?int $maintenancedataMaintenancefeesize): self
    {
        $this->maintenancedataMaintenancefeesize = $maintenancedataMaintenancefeesize;

        return $this;
    }

    public function getMaintenancedataNextinvoicedate(): ?\DateTimeInterface
    {
        return $this->maintenancedataNextinvoicedate;
    }

    public function setMaintenancedataNextinvoicedate(?\DateTimeInterface $maintenancedataNextinvoicedate): self
    {
        $this->maintenancedataNextinvoicedate = $maintenancedataNextinvoicedate;

        return $this;
    }

    public function getPaymentdataType(): ?int
    {
        return $this->paymentdataType;
    }

    public function setPaymentdataType(?int $paymentdataType): self
    {
        $this->paymentdataType = $paymentdataType;

        return $this;
    }

    public function getPaymentdataStatus(): ?int
    {
        return $this->paymentdataStatus;
    }

    public function setPaymentdataStatus(?int $paymentdataStatus): self
    {
        $this->paymentdataStatus = $paymentdataStatus;

        return $this;
    }

    public function getPaymentdataBankid(): ?string
    {
        return $this->paymentdataBankid;
    }

    public function setPaymentdataBankid(?string $paymentdataBankid): self
    {
        $this->paymentdataBankid = $paymentdataBankid;

        return $this;
    }

    public function getPaymentdataBusinessownername(): ?string
    {
        return $this->paymentdataBusinessownername;
    }

    public function setPaymentdataBusinessownername(?string $paymentdataBusinessownername): self
    {
        $this->paymentdataBusinessownername = $paymentdataBusinessownername;

        return $this;
    }

    public function getPaymentdataAffiliationnumber(): ?string
    {
        return $this->paymentdataAffiliationnumber;
    }

    public function setPaymentdataAffiliationnumber(?string $paymentdataAffiliationnumber): self
    {
        $this->paymentdataAffiliationnumber = $paymentdataAffiliationnumber;

        return $this;
    }

    public function getPaymentdataClabe(): ?string
    {
        return $this->paymentdataClabe;
    }

    public function setPaymentdataClabe(?string $paymentdataClabe): self
    {
        $this->paymentdataClabe = $paymentdataClabe;

        return $this;
    }

    public function getPaymentdataAccountnumber(): ?string
    {
        return $this->paymentdataAccountnumber;
    }

    public function setPaymentdataAccountnumber(?string $paymentdataAccountnumber): self
    {
        $this->paymentdataAccountnumber = $paymentdataAccountnumber;

        return $this;
    }

    public function getPaymentdataVmcbankfee(): ?float
    {
        return $this->paymentdataVmcbankfee;
    }

    public function setPaymentdataVmcbankfee(?float $paymentdataVmcbankfee): self
    {
        $this->paymentdataVmcbankfee = $paymentdataVmcbankfee;

        return $this;
    }

    public function getPaymentdataVmcplatformfee(): ?float
    {
        return $this->paymentdataVmcplatformfee;
    }

    public function setPaymentdataVmcplatformfee(?float $paymentdataVmcplatformfee): self
    {
        $this->paymentdataVmcplatformfee = $paymentdataVmcplatformfee;

        return $this;
    }

    public function getPaymentdataAmexbankfee(): ?float
    {
        return $this->paymentdataAmexbankfee;
    }

    public function setPaymentdataAmexbankfee(?float $paymentdataAmexbankfee): self
    {
        $this->paymentdataAmexbankfee = $paymentdataAmexbankfee;

        return $this;
    }

    public function getPaymentdataAmexplatformfee(): ?float
    {
        return $this->paymentdataAmexplatformfee;
    }

    public function setPaymentdataAmexplatformfee(?float $paymentdataAmexplatformfee): self
    {
        $this->paymentdataAmexplatformfee = $paymentdataAmexplatformfee;

        return $this;
    }

    public function getPaymentdataErrorstatus(): ?string
    {
        return $this->paymentdataErrorstatus;
    }

    public function setPaymentdataErrorstatus(?string $paymentdataErrorstatus): self
    {
        $this->paymentdataErrorstatus = $paymentdataErrorstatus;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

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

    /**
     * @return Collection|Useridentity[]
     */
    public function getCondoadmin(): Collection
    {
        return $this->condoadmin;
    }

    public function addCondoadmin(Useridentity $condoadmin): self
    {
        if (!$this->condoadmin->contains($condoadmin)) {
            $this->condoadmin[] = $condoadmin;
            $condoadmin->addCondo($this);
        }

        return $this;
    }

    public function removeCondoadmin(Useridentity $condoadmin): self
    {
        if ($this->condoadmin->contains($condoadmin)) {
            $this->condoadmin->removeElement($condoadmin);
            $condoadmin->removeCondo($this);
        }

        return $this;
    }

}
