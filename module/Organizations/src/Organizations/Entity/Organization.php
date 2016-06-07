<?php

namespace Organizations\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Users\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use Utilities\Service\Time;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Validator\NotEmpty;
use Zend\Validator\Identical;
use Utilities\Service\Validator\UniqueObject;

/**
 * Organziation Entity
 * @ORM\Entity(repositoryClass="Organizations\Entity\OrganizationRepository")
 * @ORM\Table(name="organization",uniqueConstraints={@ORM\UniqueConstraint(name="commercialName_idx", columns={"commercialName"})})
 * @Gedmo\Loggable
 * 
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int    $id
 * @property int    $status
 * @property string $commertialName
 * @property string $commertialNameAr
 * @property float  $longitude
 * @property float  $latitude
 * @property string $ownerName
 * @property string $ownerNameAr
 * @property string $ownerNationalId
 * @property string $CRNo
 * @property \DateTime $CRExpiration
 * @property \DateTime $CRExpirationHj
 * @property string $CRAttachment
 * @property string $atpLicenseNo
 * @property \DateTime $atpLicenseExpiration
 * @property \DateTime $atpLicenseExpirationHj
 * @property string $atpLicenseAttachment
 * @property string $atpWireTransferAttachment
 * @property string $atcLicenseNo
 * @property \DateTime $atcLicenseExpiration
 * @property \DateTime $atcLicenseExpirationHj
 * @property string $atcLicenseAttachment
 * @property string $atcWireTransferAttachment
 * @property string $addressLine1
 * @property string $addressLine1Ar
 * @property string $addressLine2
 * @property string $addressLine2Ar
 * @property string $city
 * @property string $cityAr
 * @property string $zipCode
 * @property string $phone1
 * @property string $phone2
 * @property string $phone3
 * @property string $fax
 * @property string $website
 * @property string $email
 * @property Users\Entity\User $trainingManager
 * @property Users\Entity\User $testCenterAdmin
 * @property Users\Entity\User $focalContactPerson
 * @property int    $labsNo
 * @property int    $pcs_lab
 * @property int    $classesNo
 * @property int    $pcs_class
 * @property int    $internetspeed
 * @property string $operatingSystem
 * @property string $operatingSystemLang
 * @property string $officeVersion
 * @property string $officeLang
 * @property string $creatorId
 * @property Doctrine\Common\Collections\ArrayCollection $organizationMetas
 * 
 * 
 * @package organizations
 * @subpackage entity
 */
class Organization
{

    /**
     * ATC
     */
    const TYPE_ATC = 1;

    /**
     * ATP
     */
    const TYPE_ATP = 2;

    /**
     * distribitror
     */
    const TYPE_DISTRIBUTOR = 3;

    /**
     * reselller
     */
    const TYPE_RESELLER = 4;

    /**
     *
     * @var Array translated properties
     */
    protected $translatedProperties = [
        'commercialName' => [
            'en_US' => 'commercialName',
            'ar_AR' => 'commercialNameAr'
        ],
        'ownerName' => [
            'en_US' => 'ownerName',
            'ar_AR' => 'ownerNameAr'
        ],
        'addressLine1' => [
            'en_US' => 'addressLine1',
            'ar_AR' => 'addressLine1Ar'
        ],
        'addressLine2' => [
            'en_US' => 'addressLine2',
            'ar_AR' => 'addressLine2Ar'
        ],
        'city' => [
            'en_US' => 'city',
            'ar_AR' => 'cityAr'
        ]
    ];

    /**
     *
     * @var InputFilter validation constraints 
     */
    private $inputFilter;

    /**
     * @Gedmo\Versioned
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    public $id;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer", nullable=true )
     * @var string
     */
    public $status;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    public $commercialName;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    public $commercialNameAr;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="float" , nullable=true )
     * @var string
     */
    public $longitude;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="float" , nullable=true)
     * @var float
     */
    public $latitude;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true )
     * @var string
     */
    public $ownerName;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true )
     * @var string
     */
    public $ownerNameAr;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true )
     * @var string
     */
    public $ownerNationalId;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true )
     * @var string
     */
    public $CRNo;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date", nullable=true )
     * @var \DateTime
     */
    public $CRExpiration;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date", nullable=true )
     * @var \DateTime
     */
    public $CRExpirationHj;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true )
     * @var string
     */
    public $CRAttachment;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $atpLicenseNo;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date" , nullable=true)
     * @var \DateTime
     */
    public $atpLicenseExpiration;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date" , nullable=true)
     * @var \DateTime
     */
    public $atpLicenseExpirationHj;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $atpLicenseAttachment;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string",nullable=true)
     * @var string
     */
    public $atpWireTransferAttachment;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $atcLicenseNo;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date" , nullable=true)
     * @var \DateTime
     */
    public $atcLicenseExpiration;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date" , nullable=true)
     * @var \DateTime
     */
    public $atcLicenseExpirationHj;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $atcLicenseAttachment;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string",nullable=true)
     * @var string
     */
    public $atcWireTransferAttachment;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true )
     * @var string
     */
    public $addressLine1;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $addressLine2;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true )
     * @var string
     */
    public $addressLine1Ar;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $addressLine2Ar;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true )
     * @var string
     */
    public $city;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true )
     * @var string
     */
    public $cityAr;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true )
     * @var string
     */
    public $zipCode;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true )
     * @var string
     */
    public $phone1;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $phone2;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $phone3;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    public $fax;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true )
     * @var string
     */
    public $website;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true )
     * @var string
     */
    public $email;

    /**
     * @ORM\OneToMany(targetEntity="Organizations\Entity\OrganizationUser", mappedBy="organization", cascade={"remove"})
     */
    public $organizationUser;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Users\Entity\User")
     * @ORM\JoinColumn(name="focalContactPerson_id", referencedColumnName="id")
     * @var Users\Entity\User
     */
    public $focalContactPerson;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer" , nullable=true)
     * @var int
     */
    public $labsNo;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    public $pcsNo_lab;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    public $classesNo;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    public $pcsNo_class;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    public $internetSpeed_lab;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    public $operatingSystem;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    public $operatingSystemLang;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    public $officeVersion;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    public $officeLang;

    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\ExamBook", mappedBy="atc")
     */
    public $exambook;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer")
     * @var int
     */
    public $creatorId;

    /**
     * @ORM\ManyToMany(targetEntity="OrganizationGovernorate", inversedBy="organizations")
     * @ORM\JoinTable(name="organization_governorates")
     */
    public $governorates;

    /**
     * @ORM\ManyToMany(targetEntity="OrganizationRegion", inversedBy="organizations")
     * @ORM\JoinTable(name="organization_regions")
     */
    public $regions;

    /**
     * @ORM\OneToMany(targetEntity="Organizations\Entity\OrganizationMeta", mappedBy="organization", cascade={"remove"}) 
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    public $organizationMetas;

    public function __construct()
    {
        $this->organizationUser = new ArrayCollection();
        $this->governorates = new ArrayCollection();
        $this->regions = new ArrayCollection();
        $this->organizationMetas = new ArrayCollection();
    }

    function getId()
    {
        return $this->id;
    }

    function getCreatorId()
    {
        return $this->creatorId;
    }

    function getCommercialName()
    {
        return $this->commercialName;
    }

    function getCommercialNameAr()
    {
        return $this->commercialNameAr;
    }

    function getLong()
    {
        return $this->longitude;
    }

    function getLat()
    {
        return $this->latitude;
    }

    function getOwnerName()
    {
        return $this->ownerName;
    }

    function getOwnerNameAr()
    {
        return $this->ownerNameAr;
    }

    function getOwnerNationalId()
    {
        return $this->ownerNationalId;
    }

    function getCRNo()
    {
        return $this->CRNo;
    }

    function getCRExpiration()
    {
        return $this->CRExpiration;
    }

    function getCRExpirationHj()
    {
        return $this->CRExpirationHj;
    }

    function getCRAttachment()
    {
        return $this->CRAttachment;
    }

    function getAtpLicenseNo()
    {
        return $this->atpLicenseNo;
    }

    function getAtpLicenseExpiration()
    {
        return $this->atpLicenseExpiration;
    }

    function getAtpLicenseExpirationHj()
    {
        return $this->atpLicenseExpirationHj;
    }

    function getAtpLicenseAttachment()
    {
        return $this->atpLicenseAttachment;
    }

    function getAtcLicenseNo()
    {
        return $this->atcLicenseNo;
    }

    function getAtcLicenseExpiration()
    {
        return $this->atcLicenseExpiration;
    }

    function getAtcLicenseExpirationHj()
    {
        return $this->atcLicenseExpirationHj;
    }

    function getAtcLicenseAttachment()
    {
        return $this->atcLicenseAttachment;
    }

    function getAtpWireTransferAttachment()
    {
        return $this->atpWireTransferAttachment;
    }

    function getAtcWireTransferAttachment()
    {
        return $this->atcWireTransferAttachment;
    }

    function setAtpWireTransferAttachment($atpWireTransferAttachment)
    {
        $this->atpWireTransferAttachment = $atpWireTransferAttachment;
    }

    function setAtcWireTransferAttachment($atcWireTransferAttachment)
    {
        $this->atcWireTransferAttachment = $atcWireTransferAttachment;
    }

    function getAddressLine1()
    {
        return $this->addressLine1;
    }

    function getAddressLine2()
    {
        return $this->addressLine2;
    }

    function getAddressLine1Ar()
    {
        return $this->addressLine1Ar;
    }

    function getAddressLine2Ar()
    {
        return $this->addressLine2Ar;
    }

    function getCity()
    {
        return $this->city;
    }

    function getGovernorates()
    {
        return $this->governorates;
    }

    function getRegions()
    {
        return $this->regions;
    }

    function setGovernorates($governorates)
    {
        $this->governorates = $governorates;
    }

    /**
     * Add Outlines
     * 
     * 
     * @access public
     * @param Courses\Entity\Outline $outline
     * @return Course
     */
    public function addGovernorate($outline)
    {
        $this->governorates[] = $outline;
        return $this;
    }

    /**
     * Remove Outlines
     * 
     * @access public
     * @param ArrayCollection $outlines
     * @return Course
     */
    public function removeGovernorate($outlines)
    {
        foreach ($outlines as $outline) {
            $outline->setOrganization(null);
            $this->governorates->removeElement($outline);
        }
        return $this;
    }

    /**
     * Set $regions
     * 
     * 
     * @access public
     * @param ArrayCollection $regions
     * @return Course
     */
    public function setRegions($regions)
    {
        $this->regions = $regions;
    }

    /**
     * Add Outlines
     * 
     * 
     * @access public
     * @param Courses\Entity\Outline $outline
     * @return Course
     */
    public function addRegions($outline)
    {
        $this->regions[] = $outline;
        return $this;
    }

    /**
     * Remove Outlines
     * 
     * @access public
     * @param ArrayCollection $outlines
     * @return Course
     */
    public function removeRegions($outlines)
    {
        foreach ($outlines as $outline) {
            $outline->setOrganization(null);
            $this->regions->removeElement($outline);
        }
        return $this;
    }

    /**
     * Set Organization metas
     * 
     * @access public
     * @param ArrayCollection $organizationMetas
     * @return Organization
     */
    public function setOrganizationMetas($organizationMetas)
    {
        $this->organizationMetas = $organizationMetas;
        return $this;
    }

    /**
     * Get Organization metas
     * 
     * @access public
     * @return ArrayCollection Organization metas
     */
    public function getOrganizationMetas()
    {
        return $this->organizationMetas;
    }

    function getCityAr()
    {
        return $this->cityAr;
    }

    function getZipCode()
    {
        return $this->zipCode;
    }

    function getPhone1()
    {
        return $this->phone1;
    }

    function getPhone2()
    {
        return $this->phone2;
    }

    function getPhone3()
    {
        return $this->phone3;
    }

    function getFax()
    {
        return $this->fax;
    }

    function getWebsite()
    {
        return $this->website;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getOrganizationUsers()
    {
        return $this->organizationUser;
    }

    function getFocalContactPerson()
    {
        return $this->focalContactPerson;
    }

    function getLabsNo()
    {
        return $this->labsNo;
    }

    function getPcsNo_lab()
    {
        return $this->pcsNo_lab;
    }

    function getClassesNo()
    {
        return $this->classesNo;
    }

    function getPcsNo_class()
    {
        return $this->pcsNo_class;
    }

    function getInternetSpeed_lab()
    {
        return $this->internetSpeed_lab;
    }

    function getOperatingSystem()
    {
        return $this->operatingSystem;
    }

    function getOperatingSystemLang()
    {
        return $this->operatingSystemLang;
    }

    function getOfficeVersion()
    {
        return $this->officeVersion;
    }

    function getOfficeLang()
    {
        return $this->officeLang;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setCreatorId($creatorId)
    {
        $this->creatorId = $creatorId;
    }

    function setCommercialName($commercialName)
    {
        $this->commercialName = $commercialName;
    }

    function setCommercialNameAr($commercialNameAr)
    {
        $this->commercialNameAr = $commercialNameAr;
    }

    function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    function setLat($latitude)
    {
        $this->latitude = $latitude;
    }

    function setOwnerName($ownerName)
    {
        $this->ownerName = $ownerName;
    }

    function setOwnerNameAr($ownerNameAr)
    {
        $this->ownerNameAr = $ownerNameAr;
    }

    function setOwnerNationalId($ownerNationalId)
    {
        $this->ownerNationalId = $ownerNationalId;
    }

    function setCRNo($CRNo)
    {
        $this->CRNo = $CRNo;
    }

    function setCRExpiration($CRExpiration)
    {
        if (!is_object($CRExpiration) && !empty($CRExpiration)) {
            $CRExpiration = \DateTime::createFromFormat(Time::DATE_FORMAT, $CRExpiration);
        }
        $this->CRExpiration = $CRExpiration;
    }

    function setCRExpirationHj($CRExpirationHj)
    {
        if (!is_object($CRExpirationHj) && !empty($CRExpirationHj)) {
            $CRExpirationHj = \DateTime::createFromFormat(Time::DATE_FORMAT, $CRExpirationHj);
        }
        $this->CRExpirationHj = $CRExpirationHj;
    }

    function setCRAttachment($CRAttachment)
    {
        $this->CRAttachment = $CRAttachment;
    }

    function setAtpLicenseNo($atpLicenseNo)
    {
        $this->atpLicenseNo = $atpLicenseNo;
    }

    function setAtpLicenseExpiration($atpLicenseExpiration)
    {
        if (!is_object($atpLicenseExpiration) && !empty($atpLicenseExpiration)) {
            $atpLicenseExpiration = \DateTime::createFromFormat(Time::DATE_FORMAT, $atpLicenseExpiration);
        }
        $this->atpLicenseExpiration = $atpLicenseExpiration;
    }

    function setAtpLicenseExpirationHj($atpLicenseExpirationHj)
    {
        if (!is_object($atpLicenseExpirationHj) && !empty($atpLicenseExpirationHj)) {
            $atpLicenseExpirationHj = \DateTime::createFromFormat(Time::DATE_FORMAT, $atpLicenseExpirationHj);
        }
        $this->atpLicenseExpirationHj = $atpLicenseExpirationHj;
    }

    function setAtpLicenseAttachment($atpLicenseAttachment)
    {
        $this->atpLicenseAttachment = $atpLicenseAttachment;
    }

    function setAtcLicenseNo($atcLicenseNo)
    {
        $this->atcLicenseNo = $atcLicenseNo;
    }

    function setAtcLicenseExpiration($atcLicenseExpiration)
    {
        if (!is_object($atcLicenseExpiration) && !empty($atcLicenseExpiration)) {
            $atcLicenseExpiration = \DateTime::createFromFormat(Time::DATE_FORMAT, $atcLicenseExpiration);
        }
        $this->atcLicenseExpiration = $atcLicenseExpiration;
    }

    function setAtcLicenseExpirationHj($atcLicenseExpirationHj)
    {
        if (!is_object($atcLicenseExpirationHj) && !empty($atcLicenseExpirationHj)) {
            $atcLicenseExpirationHj = \DateTime::createFromFormat(Time::DATE_FORMAT, $atcLicenseExpirationHj);
        }
        $this->atcLicenseExpirationHj = $atcLicenseExpirationHj;
    }

    function setAtcLicenseAttachment($atcLicenseAttachment)
    {
        $this->atcLicenseAttachment = $atcLicenseAttachment;
    }

    function setAddressLine1($addressLine1)
    {
        $this->addressLine1 = $addressLine1;
    }

    function setAddressLine2($addressLine2)
    {
        $this->addressLine2 = $addressLine2;
    }

    function setAddressLine1Ar($addressLine1Ar)
    {
        $this->addressLine1Ar = $addressLine1Ar;
    }

    function setAddressLine2Ar($addressLine2Ar)
    {
        $this->addressLine2Ar = $addressLine2Ar;
    }

    function setCity($city)
    {
        $this->city = $city;
    }

    function setCityAr($cityAr)
    {
        $this->cityAr = $cityAr;
    }

    function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    function setPhone1($phone1)
    {
        $this->phone1 = $phone1;
    }

    function setPhone2($phone2)
    {
        $this->phone2 = $phone2;
    }

    function setPhone3($phone3)
    {
        $this->phone3 = $phone3;
    }

    function setFax($fax)
    {
        $this->fax = $fax;
    }

    function setWebsite($website)
    {
        $this->website = $website;
    }

    function setEmail($email)
    {
        $this->email = $email;
    }

    function setOrganizationUser($user)
    {
        $this->organizationUser = $user;
    }

    function setFocalContactPerson(User $focalContactPerson)
    {
        $this->focalContactPerson = $focalContactPerson;
    }

    function setLabsNo($labsNo)
    {
        $this->labsNo = $labsNo;
    }

    function setPcsNo_lab($pcsNo_lab)
    {
        $this->pcsNo_lab = $pcsNo_lab;
    }

    function setClassesNo($classesNo)
    {
        $this->classesNo = $classesNo;
    }

    function setPcsNo_class($pcsNo_class)
    {
        $this->pcsNo_class = $pcsNo_class;
    }

    function setInternetSpeed_lab($internetSpeed_lab)
    {
        $this->internetSpeed_lab = $internetSpeed_lab;
    }

    function setOperatingSystem($operatingSystem)
    {
        $this->operatingSystem = $operatingSystem;
    }

    function setOperatingSystemLang($operatingSystemLang)
    {
        $this->operatingSystemLang = $operatingSystemLang;
    }

    function setOfficeVersion($officeVersion)
    {
        $this->officeVersion = $officeVersion;
    }

    function setOfficeLang($officeLang)
    {
        $this->officeLang = $officeLang;
    }

    /**
     * Set status
     * 
     * @access public
     * @param int $status
     */
    function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     * 
     * @access public
     * @return int status
     */
    public function getStatus()
    {
        return $this->status;
    }

    static function getStaticLangs()
    {
        return array(
            '1' => 'Arabic',
            '2' => 'English',
            '3' => 'Deutsch',
            '4' => 'French',
            '5' => 'Japanese',
            '6' => 'Chinese',
        );
    }

    static function getOSs()
    {
        return array(
            '1' => 'Microsoft Windows XP',
            '2' => 'Microsoft Windows Vista',
            '3' => 'Microsoft Windows 7',
            '4' => 'Microsoft Windows 8',
            '5' => 'Microsoft Windows 8.1',
            '6' => 'Microsoft Windows 10',
            '7' => 'Ubuntu Linux 13.04 LTS',
            '8' => 'Ubuntu Linux 14.04 LTS',
            '9' => 'Red Hat Enterprise Linux 5',
            '10' => 'Red Hat Enterprise Linux 6',
            '11' => 'Red Hat Enterprise Linux 7',
        );
    }

    static function getOfficeVersions()
    {
        return array(
            '1' => 'Office 2000',
            '2' => 'Office XP (2002)',
            '3' => 'Office 2003',
            '4' => 'Office 2007',
            '5' => 'Office 2010',
            '6' => 'Office 2013',
            '7' => 'Office 2016',
        );
    }

    /**
     * Convert the object to an array.
     * 
     * 
     * @access public
     * @return array current entity properties
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     * 
     * 
     * @access public
     * @param array $data ,default is empty array
     */
    public function exchangeArray($data = array())
    {
        if (array_key_exists('creatorId', $data)) {
            $this->setCreatorId($data['creatorId']);
        }
        if (array_key_exists('status', $data)) {
            $this->setStatus($data['status']);
        }
        if (array_key_exists('CRAttachment', $data) && is_string($data['CRAttachment'])) {
            $this->setCRAttachment($data["CRAttachment"]);
        }
        if (array_key_exists('atpWireTransferAttachment', $data) && is_string($data['atpWireTransferAttachment'])) {
            $this->setAtpWireTransferAttachment($data["atpWireTransferAttachment"]);
        }
        if (array_key_exists('atcWireTransferAttachment', $data) && is_string($data['atcWireTransferAttachment'])) {
            $this->setAtcWireTransferAttachment($data["atcWireTransferAttachment"]);
        }
        if (array_key_exists('region', $data)) {
            $this->setRegions($data['region']);
        }
        if (array_key_exists('governorate', $data)) {
            $this->setGovernorates($data['governorate']);
        }
        if (array_key_exists('commercialName', $data)) {
            $this->setCommercialName($data['commercialName']);
        }
        if (array_key_exists('ownerName', $data)) {
            $this->setOwnerName($data['ownerName']);
        }
        if (array_key_exists('ownerNationalId', $data)) {
            $this->setOwnerNationalId($data['ownerNationalId']);
        }
        if (array_key_exists('CRNo', $data)) {
            $this->setCRNo($data['CRNo']);
        }
        if (array_key_exists('CRExpirationHj', $data)) {
            $this->setCRExpirationHj($data['CRExpirationHj']);
        }
        if (array_key_exists('CRExpiration', $data)) {
            $this->setCRExpiration($data['CRExpiration']);
        }
        if (array_key_exists('labsNo', $data)) {
            $this->setLabsNo($data["labsNo"]);
        }
        if (array_key_exists('city', $data)) {
            $this->setCity($data['city']);
        }
        if (array_key_exists('zipCode', $data)) {
            $this->setZipCode($data['zipCode']);
        }
        if (array_key_exists('phone1', $data)) {
            $this->setPhone1($data['phone1']);
        }
        if (array_key_exists('website', $data)) {
            $this->setWebsite($data['website']);
        }
        if (array_key_exists('email', $data)) {
            $this->setEmail($data['email']);
        }
        if (array_key_exists('commercialNameAr', $data)) {
            $this->setCommercialNameAr($data['commercialNameAr']);
        }
        if (array_key_exists('CRNo', $data)) {
            $this->setCRNo($data['CRNo']);
        }
        if (array_key_exists('ownerNameAr', $data)) {
            $this->setOwnerNameAr($data['ownerNameAr']);
        }
        if (array_key_exists('focalContactPerson_id', $data)) {
            $this->focalContactPerson = $data['focalContactPerson_id'];
        }
        elseif (array_key_exists('focalContactPerson', $data)) {
            $this->focalContactPerson = $data['focalContactPerson'];
        }
        if (array_key_exists('phone2', $data)) {
            $this->setPhone2($data["phone2"]);
        }
        if (array_key_exists('phone3', $data)) {
            $this->setPhone3($data["phone3"]);
        }
        if (array_key_exists('fax', $data)) {
            $this->setFax($data["fax"]);
        }
        if (array_key_exists('addressLine1', $data)) {
            $this->setAddressLine1($data["addressLine1"]);
        }
        if (array_key_exists('addressLine1Ar', $data)) {
            $this->setAddressLine1Ar($data["addressLine1Ar"]);
        }
        if (array_key_exists('addressLine2', $data)) {
            $this->setAddressLine2($data['addressLine2']);
        }
        if (array_key_exists('addressLine2Ar', $data)) {
            $this->setAddressLine2Ar($data['addressLine2Ar']);
        }
        if (array_key_exists('city', $data)) {
            $this->setCity($data["city"]);
        }
        if (array_key_exists('cityAr', $data)) {
            $this->setCityAr($data["cityAr"]);
        }
        if (array_key_exists('zipCode', $data)) {
            $this->setZipCode($data["zipCode"]);
        }
        if (array_key_exists('phone1', $data)) {
            $this->setPhone1($data["phone1"]);
        }
        if (array_key_exists('website', $data)) {
            $this->setWebsite($data["website"]);
        }
        if (array_key_exists('email', $data)) {
            $this->setEmail($data["email"]);
        }
        if (array_key_exists('longitude', $data)) {
            $this->setLongitude($data["longitude"]);
        }
        if (array_key_exists('latitude', $data)) {
            $this->setLat($data["latitude"]);
        }
        if (array_key_exists('labsNo', $data)) {
            $this->setLabsNo($data["labsNo"]);
        }
        if (array_key_exists('pcsNo_lab', $data)) {
            $this->setPcsNo_lab($data["pcsNo_lab"]);
        }
        if (array_key_exists('classesNo', $data)) {
            $this->setClassesNo($data["classesNo"]);
        }
        if (array_key_exists('pcsNo_class', $data)) {
            $this->setPcsNo_class($data["pcsNo_class"]);
        }
        if (array_key_exists('internetSpeed_lab', $data)) {
            $this->setInternetSpeed_lab($data["internetSpeed_lab"]);
        }
        if (array_key_exists('operatingSystem', $data)) {
            $this->setOperatingSystem($data["operatingSystem"]);
        }
        if (array_key_exists('operatingSystemLang', $data)) {
            $this->setOperatingSystemLang($data["operatingSystemLang"]);
        }
        if (array_key_exists('officeVersion', $data)) {
            $this->setOfficeVersion($data["officeVersion"]);
        }
        if (array_key_exists('officeLang', $data)) {
            $this->setOfficeLang($data["officeLang"]);
        }
        if (array_key_exists('atcLicenseAttachment', $data) && is_string($data['atcLicenseAttachment'])) {
            $this->setAtcLicenseAttachment($data["atcLicenseAttachment"]);
        }
        if (array_key_exists('atcLicenseNo', $data)) {
            $this->setAtcLicenseNo($data["atcLicenseNo"]);
        }
        if (array_key_exists('atcLicenseExpiration', $data) && !empty($data['atcLicenseExpiration'])) {
            $this->setAtcLicenseExpiration($data["atcLicenseExpiration"]);
        }
        if (array_key_exists('atcLicenseExpirationHj', $data) && !empty($data['atcLicenseExpirationHj'])) {
            $this->setAtcLicenseExpirationHj($data["atcLicenseExpirationHj"]);
        }
        if (array_key_exists('atpLicenseAttachment', $data) && is_string($data['atpLicenseAttachment'])) {
            $this->setAtpLicenseAttachment($data["atpLicenseAttachment"]);
        }
        if (array_key_exists('atpLicenseNo', $data)) {
            $this->setAtpLicenseNo($data["atpLicenseNo"]);
        }
        if (array_key_exists('atpLicenseExpiration', $data) && !empty($data['atpLicenseExpiration'])) {
            $this->setAtpLicenseExpiration($data["atpLicenseExpiration"]);
        }
        if (array_key_exists('atpLicenseExpirationHj', $data) && !empty($data['atpLicenseExpirationHj'])) {
            $this->setAtpLicenseExpirationHj($data["atpLicenseExpirationHj"]);
        }
    }

    /**
     * setting inputFilter is forbidden
     * 
     * 
     * @access public
     * @param InputFilterInterface $inputFilter
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /**
     * set validation constraints
     * 
     * 
     * @uses InputFilter
     * 
     * @access public
     * @return InputFilter validation constraints
     */
    public function getInputFilter($query)
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $query->setEntity("Organizations\Entity\Organization");
            $inputFilter->add(array(
                'name' => 'longitude',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY=> 'Longitude is required',
                            ),
                        ),
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'latitude',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY=> 'Latitude is required',
                            ),
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'ownerName',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'ownerNameAr',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'CRNo',
                'required' => true,
            ));
//
            $inputFilter->add(array(
                'name' => 'ownerNationalId',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'CRExpiration',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'date',
                        'options' => array(
                            'format' => Time::DATE_FORMAT,
                        )
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'CRAttachment',
                'required' => true,
                'validators' => array(
                    array('name' => 'Filesize',
                        'options' => array(
                            'max' => 2097152
                        )
                    ),
                    array('name' => 'Fileextension',
                        'options' => array(
                            'extension' => 'gif,jpg,png,pdf,docx'
                        )
                    ),
                )
            ));

            $inputFilter->add(array(
                'name' => 'atcLicenseNo',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));
            $inputFilter->add(array(
                'name' => 'atcLicenseExpiration',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'date',
                        'options' => array(
                            'format' => Time::DATE_FORMAT,
                        )
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'atcLicenseAttachment',
                'required' => true,
                'validators' => array(
                    array('name' => 'Filesize',
                        'options' => array(
                            'max' => 2097152
                        )
                    ),
                    array('name' => 'Fileextension',
                        'options' => array(
                            'extension' => 'gif,jpg,png,pdf,docx'
                        )
                    ),
                )
            ));

            $inputFilter->add(array(
                'name' => 'city',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));


            $inputFilter->add(array(
                'name' => 'cityAr',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));


            $inputFilter->add(array(
                'name' => 'atpLicenseNo',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'atpLicenseAttachment',
                'required' => true,
                'validators' => array(
                    array('name' => 'Filesize',
                        'options' => array(
                            'max' => 2097152
                        )
                    ),
                    array('name' => 'Fileextension',
                        'options' => array(
                            'extension' => 'gif,jpg,png,pdf,docx'
                        )
                    ),
                )
            ));

            $inputFilter->add(array(
                'name' => 'atpLicenseExpiration',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'date',
                        'options' => array(
                            'format' => Time::DATE_FORMAT,
                        )
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'addressLine1',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));
            $inputFilter->add(array(
                'name' => 'addressLine2',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'addressLine1Ar',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));
            $inputFilter->add(array(
                'name' => 'addressLine2Ar',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'zipCode',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'phone1',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'phone2',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'phone3',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'website',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'fax',
                'required' => false,
            ));


            $inputFilter->add(array(
                'name' => 'email',
                'required' => true,
                'validators' => array(
                    array('name' => 'EmailAddress',
                    ),
                )
            ));
//
            $inputFilter->add(array(
                'name' => 'trainingManager_id',
                'required' => false,
            ));

            $inputFilter->add(array(
                'name' => 'testCenterAdmin_id',
                'required' => false,
            ));

            $inputFilter->add(array(
                'name' => 'focalContactPerson_id',
                'required' => true
            ));
//
            $inputFilter->add(array(
                'name' => 'labsNo',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'pcsNo_lab',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'classesNo',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'pcsNo_class',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'operatingSystem',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'operatingSystemLang',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'officeVersion',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'officeLang',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'internetSpeed_lab',
                'required' => true
            ));

            $this->inputFilter = $inputFilter;
        }
        $inputFilter->add(array(
            'name' => 'type',
            'required' => false,
        ));

        $inputFilter->add(array(
            'name' => 'commercialName',
            'required' => true,
            'validators' => array(
                array('name' => 'DoctrineModule\Validator\UniqueObject',
                    'options' => array(
                        'use_context' => true,
                        'object_manager' => $query->entityManager,
                        'object_repository' => $query->entityRepository,
                        'fields' => array('commercialName'),
                        'messages' => array(
                            UniqueObject::ERROR_OBJECT_NOT_UNIQUE => "Sorry, This commercial name already exists !"
                        ),
                    )
                ),
            )
        ));
        $inputFilter->add(array(
            'name' => 'commercialNameAr',
            'required' => true,
            'validators' => array(
                array('name' => 'DoctrineModule\Validator\UniqueObject',
                    'options' => array(
                        'use_context' => true,
                        'object_manager' => $query->entityManager,
                        'object_repository' => $query->entityRepository,
                        'fields' => array('commercialName'),
                        'messages' => array(
                            UniqueObject::ERROR_OBJECT_NOT_UNIQUE => "Sorry, This commercial name already exists !"
                        ),
                    )
                ),
            )
        ));
        
        $inputFilter->add(array(
                'name' => 'atcPrivacyStatement',
                'required' => true,
                'validators' => array(
                    array('name' => 'Identical',
                        'options' => array(
                            'token' => '1',
                            'messages' => array(
                                Identical::NOT_SAME => 'You must agree to the terms of use.',
                            ),
                        )
                    ),
                )
            ));
        
        $inputFilter->add(array(
                'name' => 'atpPrivacyStatement',
                'required' => true,
                'validators' => array(
                    array('name' => 'Identical',
                        'options' => array(
                            'token' => '1',
                            'messages' => array(
                                Identical::NOT_SAME => 'You must agree to the terms of use.',
                            ),
                        )
                    ),
                )
            ));

        return $this->inputFilter;
    }

}
