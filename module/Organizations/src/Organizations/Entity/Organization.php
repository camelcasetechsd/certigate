<?php

namespace Organizations\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Regex;
use Users\Entity\User;

/**
 * Organisation Entity
 * @ORM\Entity
 * @ORM\Table(name="organization")
 * 
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int    $id
 * @property int    $type 
 * @property string $commertialName
 * @property float  $longtitude
 * @property float  $latitude
 * @property string $ownerName
 * @property string $ownerNationalId
 * @property string $CRNo
 * @property \DateTime $CRExpiration
 * @property string $CRAttachment
 * @property string $atpLicenseNo
 * @property \DateTime $atpLicenseExpiration
 * @property string $atpLicenseAttachment
 * @property string $atcLicenseNo
 * @property \DateTime $atcLicenseExpiration
 * @property string $atcLicenseAttachment
 * @property string $addressline1
 * @property string $addressline2
 * @property string $city
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
     * both ATP & ATC
     */
    const TYPE_BOTH = 3;

    /**
     *
     * @var InputFilter validation constraints 
     */
    private $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    public $id;

    /**
     *
     * @ORM\Column(type="integer" )
     * @var int
     */
    public $type;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $commercialName;

    /**
     *
     * @ORM\Column(type="float" , nullable=true )
     * @var string
     */
    public $longtitude;

    /**
     *
     * @ORM\Column(type="float" , nullable=true)
     * @var float
     */
    public $latitude;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $ownerName;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $ownerNationalId;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $CRNo;

    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $CRExpiration;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $CRAttachment;

    /**
     *
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $atpLicenseNo;

    /**
     *
     * @ORM\Column(type="date" , nullable=true)
     * @var \DateTime
     */
    public $atpLicenseExpiration;

    /**
     *
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $atpLicenseAttachment;

    /**
     *
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $atcLicenseNo;

    /**
     *
     * @ORM\Column(type="date" , nullable=true)
     * @var \DateTime
     */
    public $atcLicenseExpiration;

    /**
     *
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $atcLicenseAttachment;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $addressLine1;

    /**
     *
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $addressLine2;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $city;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $zipCode;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $phone1;

    /**
     *
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $phone2;

    /**
     *
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $phone3;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    public $fax;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $website;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $email;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Users\Entity\User")
     * @ORM\JoinColumn(name="trainingManager_id", referencedColumnName="id")
     * @var Users\Entity\User
     */
    public $trainingManager;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Users\Entity\User")
     * @ORM\JoinColumn(name="testCenterAdmin_id", referencedColumnName="id")
     * @var Users\Entity\User
     */
    public $testCenterAdmin;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Users\Entity\User")
     * @ORM\JoinColumn(name="focalContactPerson_id", referencedColumnName="id")
     * @var Users\Entity\User
     */
    public $focalContactPerson;

    /**
     *
     * @ORM\Column(type="integer" , nullable=true)
     * @var int
     */
    public $labsNo;

    /**
     *
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    public $pcsNo_lab;

    /**
     *
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    public $classesNo;

    /**
     *
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    public $pcsNo_class;

    /**
     *
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    public $internetSpeed_lab;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    public $operatingSystem;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    public $operatingSystemLang;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    public $officeVersion;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    public $officeLang;

    function getId()
    {
        return $this->id;
    }

    function getType()
    {
        return $this->type;
    }

    function getCommercialName()
    {
        return $this->commercialName;
    }

    function getLong()
    {
        return $this->longtitude;
    }

    function getLat()
    {
        return $this->latitude;
    }

    function getOwnerName()
    {
        return $this->ownerName;
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

    function getAtcLicenseAttachment()
    {
        return $this->atcLicenseAttachment;
    }

    function getAddressLine1()
    {
        return $this->addressLine1;
    }

    function getAddressLine2()
    {
        return $this->addressLine2;
    }

    function getCity()
    {
        return $this->city;
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

    function getTrainingManager()
    {
        return $this->trainingManager;
    }

    function getTestCenterAdmin()
    {
        return $this->testCenterAdmin;
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

    function setType($type)
    {
        $this->type = $type;
    }

    function setCommercialName($commercialName)
    {
        $this->commercialName = $commercialName;
    }

    function setLongtitude($longtitude)
    {
        $this->longtitude = $longtitude;
    }

    function setLat($latitude)
    {
        $this->latitude = $latitude;
    }

    function setOwnerName($ownerName)
    {
        $this->ownerName = $ownerName;
    }

    function setOwnerNationalId($ownerNationalId)
    {
        $this->ownerNationalId = $ownerNationalId;
    }

    function setCRNo($CRNo)
    {
        $this->CRNo = $CRNo;
    }

    function setCRExpiration(\DateTime $CRExpiration)
    {
        $this->CRExpiration = $CRExpiration;
    }

    function setCRAttachment($CRAttachment)
    {
        $this->CRAttachment = $CRAttachment;
    }

    function setAtpLicenseNo($atpLicenseNo)
    {
        $this->atpLicenseNo = $atpLicenseNo;
    }

    function setAtpLicenseExpiration(\DateTime $atpLicenseExpiration)
    {
        $this->atpLicenseExpiration = $atpLicenseExpiration;
    }

    function setAtpLicenseAttachment($atpLicenseAttachment)
    {
        $this->atpLicenseAttachment = $atpLicenseAttachment;
    }

    function setAtcLicenseNo($atcLicenseNo)
    {
        $this->atcLicenseNo = $atcLicenseNo;
    }

    function setAtcLicenseExpiration(\DateTime $atcLicenseExpiration)
    {
        $this->atcLicenseExpiration = $atcLicenseExpiration;
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

    function setCity($city)
    {
        $this->city = $city;
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

    function setTrainingManager(User $trainingManager)
    {
        $this->trainingManager = $trainingManager;
    }

    function setTestCenterAdmin(User $testCenterAdmin)
    {
        $this->testCenterAdmin = $testCenterAdmin;
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
        $this->type = (int) $data['type'];
        $this->commercialName = $data['commercialName'];
        $this->ownerName = $data['ownerName'];
        $this->ownerNationalId = $data['ownerNationalId'];
        $this->CRNo = $data['CRNo'];
        $this->CRExpiration = $data['CRExpiration'];
        $this->CRAttachment = $data['CRAttachment'];
        $this->atpLicenseNo = (!empty($data['atpLicenseNo'])) ?  (int)$data['atpLicenseNo'] : null;
        $this->atpLicenseExpiration = (!empty($data['atpLicenseExpiration'])) ? $data['atpLicenseExpiration'] : null;
        $this->atpLicenseAttachment = (!empty($data['atpLicenseAttachment'])) ? $data['atpLicenseAttachment'] : null;
        $this->atcLicenseNo = (!empty($data['atcLicenseNo'])) ? $data['atcLicenseNo'] : null;
        $this->atcLicenseExpiration =(!empty($data['atcLicenseExpiration'])) ? $data['atcLicenseExpiration'] : null;
        $this->atcLicenseAttachment = (!empty($data['atcLicenseAttachment'])) ? $data['atcLicenseAttachment'] : null;
        $this->addressLine1 = $data['addressLine1'];
        $this->addressLine2 = (!empty($data['addressLine2'])) ? $data['addressLine2'] : null;
        $this->city = $data['city'];
        $this->zipCode = $data['zipCode'];
        $this->phone1 = $data['phone1'];
        $this->phone2 = (!empty($data['phone2'])) ? $data['phone2'] : null;
        $this->phone3 = (!empty($data['phone3'])) ? $data['phone3'] : null;
        $this->fax = (!empty($data['fax'])) ? $data['fax'] : null;
        $this->website = $data['website'];
        $this->email = $data['email'];
        $this->trainingManager = $data['trainingManager_id'] != 0 ? $data['trainingManager_id'] : null;
        $this->testCenterAdmin = $data['testCenterAdmin_id'] != 0 ? $data['testCenterAdmin_id'] : null;
        $this->focalContactPerson = $data['focalContactPerson_id'];
        $this->labsNo = (!empty($data['labsNo'])) ? (int) $data['labsNo'] : null;
        $this->classesNo = (!empty($data['classesNo'])) ? (int) $data['classesNo'] : null;
        $this->pcsNo_lab = (!empty($data['pcsNo_lab'])) ? (int) $data['pcsNo_lab'] : null;
        $this->pcsNo_class = (!empty($data['pcsNo_class'])) ? (int) $data['pcsNo_class'] : null;
        $this->internetSpeed_lab = (!empty($data['internetSpeed_lab'])) ? (int) $data['internetSpeed_lab'] : null;
        $this->operatingSystem = (!empty($data['operatingSystem'])) ? $data['operatingSystem'] : null;
        $this->operatingSystemLang = (!empty($data['operatingSystemLang'])) ? $data['operatingSystemLang'] : null;
        $this->officeVersion = (!empty($data['officeVersion'])) ? $data['officeVersion'] : null;
        $this->officeLang = (!empty($data['officeLang'])) ? $data['officeLang'] : null;
        $this->longtitude = (double) $data['long'];
        $this->latitude = (double) $data['lat'];
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
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'commercialName',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'type',
            ));

            $inputFilter->add(array(
                'name' => 'longtitude',
            ));

            $inputFilter->add(array(
                'name' => 'latitude',
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
                'name' => 'CRNo',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

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
                            'format' => 'm/d/Y',
                        )
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'CRAttachment',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'atcLicenseNo',
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'atcLicenseAttachment',
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'atcLicenseExpiration',
                'validators' => array(
                    array(
                        'name' => 'date',
                        'options' => array(
                            'format' => 'm/d/Y',
                        )
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'atpLicenseNo',
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'atpLicenseAttachment',
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'atpLicenseExpiration',
                'validators' => array(
                    array(
                        'name' => 'date',
                        'options' => array(
                            'format' => 'm/d/Y',
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
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'phone3',
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
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'tariningManager',
            ));

            $inputFilter->add(array(
                'name' => 'testCenterAdmin',
            ));

            $inputFilter->add(array(
                'name' => 'focalContactPerson',
            ));

            $inputFilter->add(array(
                'name' => 'labsNo',
                'required' => true
            ));

            $inputFilter->add(array(
                'name' => 'pcsNo_lab',
                'required' => true
            ));

            $inputFilter->add(array(
                'name' => 'classesNo',
                'required' => true
            ));

            $inputFilter->add(array(
                'name' => 'pcsNo_class',
                'required' => true
            ));

            $inputFilter->add(array(
                'name' => 'operatingSystem',
                'required' => true
            ));

            $inputFilter->add(array(
                'name' => 'operatingSystemLang',
                'required' => true
            ));

            $inputFilter->add(array(
                'name' => 'officeVersion',
                'required' => true
            ));

            $inputFilter->add(array(
                'name' => 'officeLang',
                'required' => true
            ));

            $inputFilter->add(array(
                'name' => '$internetSpeed_lab',
                'required' => true
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
