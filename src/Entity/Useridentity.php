<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Useridentity
 *
 * @ORM\Table(name="useridentity", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_a797af1de7927c74", columns={"email"}), @ORM\UniqueConstraint(name="uniq_a797af1dd80a74e3", columns={"refreshtoken_token"}), @ORM\UniqueConstraint(name="uniq_a797af1d60f55a75", columns={"resetpasswordlink"}), @ORM\UniqueConstraint(name="uniq_a797af1dbf396750", columns={"id"}), @ORM\UniqueConstraint(name="uniq_a797af1d66a8502", columns={"initialpasswordlink"})}, indexes={@ORM\Index(name="idx_a797af1df8bd700d", columns={"unit_id"}), @ORM\Index(name="idx_a797af1d9b6b5fba", columns={"account_id"}), @ORM\Index(name="idx_a797af1d3da5256d", columns={"image_id"})})
 * @ORM\Entity
 */
class Useridentity
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="useridentity_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=false)
     */
    private $lastname;

    /**
     * @var bool
     *
     * @ORM\Column(name="isactive", type="boolean", nullable=false)
     */
    private $isactive;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=false)
     */
    private $role;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime", nullable=false)
     */
    private $createdat;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="initializedat", type="datetime", nullable=true)
     */
    private $initializedat;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="lastactivateddeactivatedat", type="datetime", nullable=true)
     */
    private $lastactivateddeactivatedat;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="lastloginat", type="datetime", nullable=true)
     */
    private $lastloginat;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="lastactiveat", type="datetime", nullable=true)
     */
    private $lastactiveat;

    /**
     * @var int|null
     *
     * @ORM\Column(name="passwordstatus", type="integer", nullable=true)
     */
    private $passwordstatus;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="passwordstatuschangedat", type="datetime", nullable=true)
     */
    private $passwordstatuschangedat;

    /**
     * @var string|null
     *
     * @ORM\Column(name="initialpasswordlink", type="string", length=255, nullable=true)
     */
    private $initialpasswordlink;

    /**
     * @var string|null
     *
     * @ORM\Column(name="resetpasswordlink", type="string", length=255, nullable=true)
     */
    private $resetpasswordlink;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=false)
     */
    private $phone;

    /**
     * @var bool
     *
     * @ORM\Column(name="isnotificationsenabled", type="boolean", nullable=false)
     */
    private $isnotificationsenabled;

    /**
     * @var string
     *
     * @ORM\Column(name="refreshtoken_token", type="string", length=255, nullable=false)
     */
    private $refreshtokenToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="refreshtoken_expirationtime", type="datetime", nullable=false)
     */
    private $refreshtokenExpirationtime;

    /**
     * @var string
     *
     * @ORM\Column(name="user_type", type="string", length=255, nullable=false)
     */
    private $userType;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="isprimary", type="boolean", nullable=true)
     */
    private $isprimary;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    private $birthday;

    /**
     * @var string|null
     *
     * @ORM\Column(name="gender", type="string", length=255, nullable=true)
     */
    private $gender;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="homephone", type="string", length=255, nullable=true)
     */
    private $homephone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cellphone", type="string", length=255, nullable=true)
     */
    private $cellphone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="relationship", type="string", length=255, nullable=true)
     */
    private $relationship;

    /**
     * @var string|null
     *
     * @ORM\Column(name="paymentproviderid", type="string", length=255, nullable=true)
     */
    private $paymentproviderid;

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
     * @var \Unit
     *
     * @ORM\ManyToOne(targetEntity="Unit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unit_id", referencedColumnName="id")
     * })
     */
    private $unit;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Condo", inversedBy="condoadmin")
     * @ORM\JoinTable(name="condoadmin_condo",
     *   joinColumns={
     *     @ORM\JoinColumn(name="condoadmin_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="condo_id", referencedColumnName="id")
     *   }
     * )
     */
    private $condo;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->condo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getIsactive(): ?bool
    {
        return $this->isactive;
    }

    public function setIsactive(bool $isactive): self
    {
        $this->isactive = $isactive;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

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

    public function getInitializedat(): ?\DateTimeInterface
    {
        return $this->initializedat;
    }

    public function setInitializedat(?\DateTimeInterface $initializedat): self
    {
        $this->initializedat = $initializedat;

        return $this;
    }

    public function getLastactivateddeactivatedat(): ?\DateTimeInterface
    {
        return $this->lastactivateddeactivatedat;
    }

    public function setLastactivateddeactivatedat(?\DateTimeInterface $lastactivateddeactivatedat): self
    {
        $this->lastactivateddeactivatedat = $lastactivateddeactivatedat;

        return $this;
    }

    public function getLastloginat(): ?\DateTimeInterface
    {
        return $this->lastloginat;
    }

    public function setLastloginat(?\DateTimeInterface $lastloginat): self
    {
        $this->lastloginat = $lastloginat;

        return $this;
    }

    public function getLastactiveat(): ?\DateTimeInterface
    {
        return $this->lastactiveat;
    }

    public function setLastactiveat(?\DateTimeInterface $lastactiveat): self
    {
        $this->lastactiveat = $lastactiveat;

        return $this;
    }

    public function getPasswordstatus(): ?int
    {
        return $this->passwordstatus;
    }

    public function setPasswordstatus(?int $passwordstatus): self
    {
        $this->passwordstatus = $passwordstatus;

        return $this;
    }

    public function getPasswordstatuschangedat(): ?\DateTimeInterface
    {
        return $this->passwordstatuschangedat;
    }

    public function setPasswordstatuschangedat(?\DateTimeInterface $passwordstatuschangedat): self
    {
        $this->passwordstatuschangedat = $passwordstatuschangedat;

        return $this;
    }

    public function getInitialpasswordlink(): ?string
    {
        return $this->initialpasswordlink;
    }

    public function setInitialpasswordlink(?string $initialpasswordlink): self
    {
        $this->initialpasswordlink = $initialpasswordlink;

        return $this;
    }

    public function getResetpasswordlink(): ?string
    {
        return $this->resetpasswordlink;
    }

    public function setResetpasswordlink(?string $resetpasswordlink): self
    {
        $this->resetpasswordlink = $resetpasswordlink;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getIsnotificationsenabled(): ?bool
    {
        return $this->isnotificationsenabled;
    }

    public function setIsnotificationsenabled(bool $isnotificationsenabled): self
    {
        $this->isnotificationsenabled = $isnotificationsenabled;

        return $this;
    }

    public function getRefreshtokenToken(): ?string
    {
        return $this->refreshtokenToken;
    }

    public function setRefreshtokenToken(string $refreshtokenToken): self
    {
        $this->refreshtokenToken = $refreshtokenToken;

        return $this;
    }

    public function getRefreshtokenExpirationtime(): ?\DateTimeInterface
    {
        return $this->refreshtokenExpirationtime;
    }

    public function setRefreshtokenExpirationtime(\DateTimeInterface $refreshtokenExpirationtime): self
    {
        $this->refreshtokenExpirationtime = $refreshtokenExpirationtime;

        return $this;
    }

    public function getUserType(): ?string
    {
        return $this->userType;
    }

    public function setUserType(string $userType): self
    {
        $this->userType = $userType;

        return $this;
    }

    public function getIsprimary(): ?bool
    {
        return $this->isprimary;
    }

    public function setIsprimary(?bool $isprimary): self
    {
        $this->isprimary = $isprimary;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getHomephone(): ?string
    {
        return $this->homephone;
    }

    public function setHomephone(?string $homephone): self
    {
        $this->homephone = $homephone;

        return $this;
    }

    public function getCellphone(): ?string
    {
        return $this->cellphone;
    }

    public function setCellphone(?string $cellphone): self
    {
        $this->cellphone = $cellphone;

        return $this;
    }

    public function getRelationship(): ?string
    {
        return $this->relationship;
    }

    public function setRelationship(?string $relationship): self
    {
        $this->relationship = $relationship;

        return $this;
    }

    public function getPaymentproviderid(): ?string
    {
        return $this->paymentproviderid;
    }

    public function setPaymentproviderid(?string $paymentproviderid): self
    {
        $this->paymentproviderid = $paymentproviderid;

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

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection|Condo[]
     */
    public function getCondo(): Collection
    {
        return $this->condo;
    }

    public function addCondo(Condo $condo): self
    {
        if (!$this->condo->contains($condo)) {
            $this->condo[] = $condo;
        }

        return $this;
    }

    public function removeCondo(Condo $condo): self
    {
        if ($this->condo->contains($condo)) {
            $this->condo->removeElement($condo);
        }

        return $this;
    }

}
