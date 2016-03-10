<?php

namespace Users\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Utilities\Service\Time;
use DoctrineModule\Validator\UniqueObject;
use Utilities\Service\Inflector;
use Utilities\Service\Status;
use Zend\Validator\Identical;

/**
 * User Entity
 * @ORM\Entity(repositoryClass="Users\Entity\UserRepository")
 * @ORM\Table(name="user",uniqueConstraints={
 * @ORM\UniqueConstraint(name="username_idx", columns={"username"}),
 * @ORM\UniqueConstraint(name="email_idx", columns={"email"})
 * })
 * 
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property string $firstName
 * @property string $middleName
 * @property string $lastName
 * @property string $country
 * @property string $language
 * @property string $username
 * @property string $password
 * @property string $mobile
 * @property \DateTime $dateOfBirth
 * @property string $addressOne
 * @property string $addressTwo
 * @property string $city
 * @property string $zipCode
 * @property string $phone
 * @property string $nationality
 * @property string $identificationType
 * @property string $identificationNumber
 * @property \DateTime $identificationExpiryDate
 * @property string $email
 * @property string $securityQuestion
 * @property string $securityAnswer
 * @property string $photo
 * @property array $roles
 * @property int $privacyStatement
 * @property int $studentStatement
 * @property int $proctorStatement
 * @property int $instructorStatement
 * @property int $testCenterAdministratorStatement
 * @property int $trainingManagerStatement
 * @property int $status
 * @property Doctrine\Common\Collections\ArrayCollection $courseEventUsers
 * 
 * @package users
 * @subpackage entity
 */
class User
{

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
     * @ORM\Column(type="string")
     * @var string
     */
    public $firstName;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $middleName;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $lastName;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $country;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $language;

    /**
     *
     * @ORM\Column(type="string" , unique=true)
     * @var string
     */
    public $username;

    /**
     *
     * @ORM\Column(type="string" , length =64)
     * @var string
     */
    public $password;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $mobile;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $addressOne;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $addressTwo;

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
    public $phone;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $nationality;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $identificationType;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $identificationNumber;

    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $identificationExpiryDate;

    /**
     *
     * @ORM\Column(type="string" , unique=true)
     * @var string
     */
    public $email;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $securityQuestion;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $securityAnswer;

    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $dateOfBirth;

    /**
     *
     * @ORM\Column(type="string" , nullable=true)
     * @var string
     */
    public $photo;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Users\Entity\Role")
     * @var array Users\Entity\Role
     */
    public $roles;

    /**
     *
     * @ORM\Column(type="integer")
     * @var int
     */
    public $privacyStatement;

    /**
     *
     * @ORM\Column(type="integer")
     * @var int
     */
    public $studentStatement;

    /**
     *
     * @ORM\Column(type="integer")
     * @var int
     */
    public $proctorStatement;

    /**
     *
     * @ORM\Column(type="integer")
     * @var int
     */
    public $instructorStatement;

    /**
     *
     * @ORM\Column(type="integer")
     * @var int
     */
    public $testCenterAdministratorStatement;

    /**
     *
     * @ORM\Column(type="integer")
     * @var int
     */
    public $trainingManagerStatement;

    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\CourseEventUser", mappedBy="user")
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    public $courseEventUsers;

    /**
     *
     * @ORM\Column(type="integer")
     * @var int
     */
    public $status;

    /**
     * @ORM\OneToMany(targetEntity="Organizations\Entity\OrganizationUser", mappedBy="user")
     */
    public $organizationUser;

    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\Vote", mappedBy="user")
     */
    public $votes;

    /**
     * hash password
     * 
     * 
     * @access public
     * @param string $password
     * @return string hashed password
     */
    static public function hashPassword($password)
    {
        if (function_exists("password_hash")) {
            return password_hash($password, PASSWORD_BCRYPT);
        }
        else {
            return crypt($password);
        }
    }

    /**
     * verify submitted password matches the saved one
     * 
     * 
     * @access public
     * @param string $givenPassword
     * @param string $savedPassword hashed password
     * @return bool true if passwords mathced, false else
     */
    static public function verifyPassword($givenPassword, $savedPassword)
    {
        if (function_exists('password_verify')) {
            return password_verify($givenPassword, $savedPassword);
        }
        else {
            return crypt($givenPassword, $savedPassword) == $savedPassword;
        }
    }

    /**
     * Prepare user entity
     * 
     * 
     * @access public
     */
    public function __construct()
    {
        $this->courseEventUsers = new ArrayCollection();
        $this->organizationUser = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    /**
     * Get id
     * 
     * 
     * @access public
     * @return int id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get dateOfBirth
     * 
     * 
     * @access public
     * @return \DateTime dateOfBirth
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Get mobile
     * 
     * 
     * @access public
     * @return string mobile
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Get firstName
     * 
     * 
     * @access public
     * @return string firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Get middleName
     * 
     * 
     * @access public
     * @return string middleName
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Get lastName
     * 
     * 
     * @access public
     * @return string lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get country
     * 
     * 
     * @access public
     * @return string country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Get language
     * 
     * 
     * @access public
     * @return string language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Get fullName
     * 
     * 
     * @access public
     * @return string fullName
     */
    public function getFullName()
    {
        $fullName = $this->getFirstName();
        if (!empty($this->getMiddleName())) {
            $fullName .= " " . $this->getMiddleName();
        }
        $fullName .= " " . $this->getLastName();
        return $fullName;
    }

    /**
     * Get password
     * 
     * 
     * @access public
     * @return string password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get photo
     * 
     * 
     * @access public
     * @return string photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Get roles
     * 
     * 
     * @access public
     * @return ArrayCollection Users\Entity\Role roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Get roles names
     * 
     * 
     * @access public
     * @return array roles names
     */
    public function getRolesNames()
    {
        $rolesNames = array();
        foreach ($this->roles as $role) {
            if (is_object($role)) {
                $rolesNames[] = $role->getName();
            }
        }
        return $rolesNames;
    }

    /**
     * Get roles agreements status
     * 
     * 
     * @access public
     * @return array agreement status per each role
     */
    public function getRolesAgreementsStatus()
    {
        $inflector = new Inflector();
        $roles = $this->getRolesNames();
        $rolesAgreementsStatus = array();
        foreach ($roles as $role) {
            $roleAgrementStatusMethod = "get" . $inflector->camelize($role) . "Statement";
            if (method_exists($this, $roleAgrementStatusMethod)) {
                $rolesAgreementsStatus[$role] = $this->$roleAgrementStatusMethod();
            }
        }
        return $rolesAgreementsStatus;
    }

    /**
     * Get status
     * 
     * 
     * @access public
     * @return int status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get privacyStatement
     * 
     * 
     * @access public
     * @return int privacyStatement
     */
    public function getPrivacyStatement()
    {
        return $this->privacyStatement;
    }

    /**
     * Get studentStatement
     * 
     * 
     * @access public
     * @return int studentStatement
     */
    public function getStudentStatement()
    {
        return $this->studentStatement;
    }

    /**
     * Get proctorStatement
     * 
     * 
     * @access public
     * @return int proctorStatement
     */
    public function getProctorStatement()
    {
        return $this->proctorStatement;
    }

    /**
     * Get instructorStatement
     * 
     * 
     * @access public
     * @return int instructorStatement
     */
    public function getInstructorStatement()
    {
        return $this->instructorStatement;
    }

    /**
     * Get testCenterAdministratorStatement
     * 
     * 
     * @access public
     * @return int testCenterAdministratorStatement
     */
    public function getTestCenterAdministratorStatement()
    {
        return $this->testCenterAdministratorStatement;
    }

    /**
     * Get trainingManagerStatement
     * 
     * 
     * @access public
     * @return int trainingManagerStatement
     */
    public function getTrainingManagerStatement()
    {
        return $this->trainingManagerStatement;
    }

    /**
     * Get username
     * 
     * 
     * @access public
     * @return string username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get addressOne
     * 
     * 
     * @access public
     * @return string addressOne
     */
    public function getAddressOne()
    {
        return $this->addressOne;
    }

    /**
     * Get addressTwo
     * 
     * 
     * @access public
     * @return string addressTwo
     */
    public function getAddressTwo()
    {
        return $this->addressTwo;
    }

    /**
     * Get city
     * 
     * 
     * @access public
     * @return string city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get zipCode
     * 
     * 
     * @access public
     * @return string zipCode
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Get phone
     * 
     * 
     * @access public
     * @return string phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get nationality
     * 
     * 
     * @access public
     * @return string nationality
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * Get identificationType
     * 
     * 
     * @access public
     * @return string identificationType
     */
    public function getIdentificationType()
    {
        return $this->identificationType;
    }

    /**
     * Get identificationNumber
     * 
     * 
     * @access public
     * @return string identificationNumber
     */
    public function getIdentificationNumber()
    {
        return $this->identificationNumber;
    }

    /**
     * Get identificationExpiryDate
     * 
     * 
     * @access public
     * @return \DateTime identificationExpiryDate
     */
    public function getIdentificationExpiryDate()
    {
        return $this->identificationExpiryDate;
    }

    /**
     * Get email
     * 
     * 
     * @access public
     * @return string email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get securityQuestion
     * 
     * 
     * @access public
     * @return string securityQuestion
     */
    public function getSecurityQuestion()
    {
        return $this->securityQuestion;
    }

    /**
     * Get securityAnswer
     * 
     * 
     * @access public
     * @return string securityAnswer
     */
    public function getSecurityAnswer()
    {
        return $this->securityAnswer;
    }

    /**
     * Set dateOfBirth
     * 
     * 
     * @access public
     * @param \DateTime $dateOfBirth
     * @return User current entity
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = \DateTime::createFromFormat(Time::DATE_FORMAT, $dateOfBirth);
        return $this;
    }

    /**
     * Set mobile
     * 
     * 
     * @access public
     * @param string $mobile
     * @return User current entity
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * Set firstName
     * 
     * 
     * @access public
     * @param string $firstName
     * @return User current entity
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Set middleName
     * 
     * 
     * @access public
     * @param string $middleName
     * @return User current entity
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
        return $this;
    }

    /**
     * Set lastName
     * 
     * 
     * @access public
     * @param string $lastName
     * @return User current entity
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Set country
     * 
     * 
     * @access public
     * @param string $country
     * @return User current entity
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Set language
     * 
     * 
     * @access public
     * @param string $language
     * @return User current entity
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Set password
     * 
     * 
     * @access public
     * @param string $password
     * @return User current entity
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Set photo
     * 
     * 
     * @access public
     * @param string $photo
     * @return User current entity
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * add role
     * 
     * 
     * @access public
     * @param Users/Entity/Role $role
     * @return User current entity
     */
    public function addRole($role)
    {
        $this->roles[] = $role;
        return $this;
    }

    /**
     * Set roles
     * 
     * 
     * @access public
     * @param array $roles array of Users\Entity\Role instances or just ids
     * @return User current entity
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Set status
     * 
     * 
     * @access public
     * @param int $status
     * @return User current entity
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Set privacyStatement
     * 
     * 
     * @access public
     * @param int $privacyStatement
     * @return User current entity
     */
    public function setPrivacyStatement($privacyStatement)
    {
        $this->privacyStatement = $privacyStatement;
        return $this;
    }

    /**
     * Set studentStatement
     * 
     * 
     * @access public
     * @param int $studentStatement
     * @return User current entity
     */
    public function setStudentStatement($studentStatement)
    {
        $this->studentStatement = $studentStatement;
        return $this;
    }

    /**
     * Set proctorStatement
     * 
     * 
     * @access public
     * @param int $proctorStatement
     * @return User current entity
     */
    public function setProctorStatement($proctorStatement)
    {
        $this->proctorStatement = $proctorStatement;
        return $this;
    }

    /**
     * Set instructorStatement
     * 
     * 
     * @access public
     * @param int $instructorStatement
     * @return User current entity
     */
    public function setInstructorStatement($instructorStatement)
    {
        $this->instructorStatement = $instructorStatement;
        return $this;
    }

    /**
     * Set testCenterAdministratorStatement
     * 
     * 
     * @access public
     * @param int $testCenterAdministratorStatement
     * @return User current entity
     */
    public function setTestCenterAdministratorStatement($testCenterAdministratorStatement)
    {
        $this->testCenterAdministratorStatement = $testCenterAdministratorStatement;
        return $this;
    }

    /**
     * Set trainingManagerStatement
     * 
     * 
     * @access public
     * @param int $trainingManagerStatement
     * @return User current entity
     */
    public function setTrainingManagerStatement($trainingManagerStatement)
    {
        $this->trainingManagerStatement = $trainingManagerStatement;
        return $this;
    }

    /**
     * Set username
     * 
     * 
     * @access public
     * @param string $username
     * @return User current entity
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Set addressOne
     * 
     * 
     * @access public
     * @param string $addressOne
     * @return User current entity
     */
    public function setAddressOne($addressOne)
    {
        $this->addressOne = $addressOne;
        return $this;
    }

    /**
     * Set addressTwo
     * 
     * 
     * @access public
     * @param string $addressTwo
     * @return User current entity
     */
    public function setAddressTwo($addressTwo)
    {
        $this->addressTwo = $addressTwo;
        return $this;
    }

    /**
     * Set city
     * 
     * 
     * @access public
     * @param string $city
     * @return User current entity
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Set zipCode
     * 
     * 
     * @access public
     * @param string $zipCode
     * @return User current entity
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * Set phone
     * 
     * 
     * @access public
     * @param string $phone
     * @return User current entity
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Set nationality
     * 
     * 
     * @access public
     * @param string $nationality
     * @return User current entity
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;
        return $this;
    }

    /**
     * Set identificationType
     * 
     * 
     * @access public
     * @param string $identificationType
     * @return User current entity
     */
    public function setIdentificationType($identificationType)
    {
        $this->identificationType = $identificationType;
        return $this;
    }

    /**
     * Set identificationNumber
     * 
     * 
     * @access public
     * @param string $identificationNumber
     * @return User current entity
     */
    public function setIdentificationNumber($identificationNumber)
    {
        $this->identificationNumber = $identificationNumber;
        return $this;
    }

    /**
     * Set identificationExpiryDate
     * 
     * 
     * @access public
     * @param \DateTime $identificationExpiryDate
     * @return User current entity
     */
    public function setIdentificationExpiryDate($identificationExpiryDate)
    {
        $this->identificationExpiryDate = \DateTime::createFromFormat(Time::DATE_FORMAT, $identificationExpiryDate);
        return $this;
    }

    /**
     * Set email
     * 
     * 
     * @access public
     * @param string $email
     * @return User current entity
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Set securityQuestion
     * 
     * 
     * @access public
     * @param string $securityQuestion
     * @return User current entity
     */
    public function setSecurityQuestion($securityQuestion)
    {
        $this->securityQuestion = $securityQuestion;
        return $this;
    }

    /**
     * Set securityAnswer
     * 
     * 
     * @access public
     * @param string $securityAnswer
     * @return User current entity
     */
    public function setSecurityAnswer($securityAnswer)
    {
        $this->securityAnswer = $securityAnswer;
        return $this;
    }

    /**
     * Get CourseEventUsers
     * 
     * 
     * @access public
     * @return ArrayCollection courseEventUsers
     */
    public function getCourseEventUsers()
    {
        return $this->courseEventUsers;
    }

    /**
     * Add CourseEventUsers
     * 
     * 
     * @access public
     * @param Courses\Entity\CourseEventUser $courseEventUser
     * @return User
     */
    public function addCourseEventUsers($courseEventUser)
    {
        $this->courseEventUsers[] = $courseEventUser;
        return $this;
    }

    /**
     * Set CourseEventUsers
     * 
     * 
     * @access public
     * @param ArrayCollection $courseEventUsers
     * @return User
     */
    public function setCourseEventUsers($courseEventUsers)
    {
        $this->courseEventUsers = $courseEventUsers;
        return $this;
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
        if (array_key_exists('roles', $data)) {
            $this->setRoles($data["roles"]);
        }
        if (array_key_exists('status', $data)) {
            $this->setStatus($data["status"]);
        }
        if (array_key_exists('photo', $data) && is_string($data['photo'])) {
            $this->setPhoto($data["photo"]);
        }
        if (array_key_exists('password', $data) && !empty($data['password'])) {
            $this->setPassword($data["password"]);
        }
        $this->setDateOfBirth($data["dateOfBirth"])
                ->setMobile($data["mobile"])
                ->setFirstName($data["firstName"])
                ->setLastName($data["lastName"])
                ->setMiddleName($data["middleName"])
                ->setCountry($data["country"])
                ->setLanguage($data["language"])
                ->setUsername($data["username"])
                ->setAddressOne($data["addressOne"])
                ->setAddressTwo($data["addressTwo"])
                ->setCity($data["city"])
                ->setEmail($data["email"])
                ->setIdentificationExpiryDate($data["identificationExpiryDate"])
                ->setIdentificationNumber($data["identificationNumber"])
                ->setIdentificationType($data["identificationType"])
                ->setNationality($data["nationality"])
                ->setPhone($data["phone"])
                ->setSecurityAnswer($data["securityAnswer"])
                ->setSecurityQuestion($data["securityQuestion"])
                ->setZipCode($data["zipCode"])
                ->setPrivacyStatement($data["privacyStatement"])
                ->setStudentStatement($data["studentStatement"])
                ->setProctorStatement($data["proctorStatement"])
                ->setInstructorStatement($data["instructorStatement"])
                ->setTestCenterAdministratorStatement($data["testCenterAdministratorStatement"])
                ->setTrainingManagerStatement($data["trainingManagerStatement"])
        ;
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
     * @param Utilities\Service\Query\Query $query
     * @return InputFilter validation constraints
     */
    public function getInputFilter($query)
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'username',
                'required' => true,
                'validators' => array(
                    array('name' => 'DoctrineModule\Validator\UniqueObject',
                        'options' => array(
                            'use_context' => true,
                            'object_manager' => $query->entityManager,
                            'object_repository' => $query->entityRepository,
                            'fields' => array('username'),
                            'messages' => array(UniqueObject::ERROR_OBJECT_NOT_UNIQUE => "This username is already in use")
                        )
                    ),
                ),
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));
            $inputFilter->add(array(
                'name' => 'password',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 8
                        )
                    )
                )
            ));
            $inputFilter->add(array(
                'name' => 'confirmPassword',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 8
                        )
                    )
                )
            ));
            $inputFilter->add(array(
                'name' => 'firstName',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));
            $inputFilter->add(array(
                'name' => 'lastName',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));
            $inputFilter->add(array(
                'name' => 'middleName',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));
            $inputFilter->add(array(
                'name' => 'country',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'language',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'mobile',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^\b\d{3}[-.]?\d{3}[-.]?\d{4}\b$/',
                            'messages' => array(
                                \Zend\Validator\Regex::NOT_MATCH => 'Please enter valid mobile number!'
                            )
                        ))
                )
            ));
            $inputFilter->add(array(
                'name' => 'dateOfBirth',
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
                'name' => 'photo',
                'required' => false,
                'validators' => array(
                    array('name' => 'Filesize',
                        'options' => array(
                            'max' => 2097152
                        )
                    ),
                    array('name' => 'Fileextension',
                        'options' => array(
                            'extension' => 'gif,jpg,png'
                        )
                    ),
                )
            ));

            $inputFilter->add(array(
                'name' => 'addressOne',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'addressTwo',
                'required' => false,
            ));
            $inputFilter->add(array(
                'name' => 'zipCode',
                'required' => false,
            ));
            $inputFilter->add(array(
                'name' => 'phone',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^\b\d{3}[-.]?\d{3}[-.]?\d{4}\b$/',
                            'messages' => array(
                                \Zend\Validator\Regex::NOT_MATCH => 'Please enter valid phone number!'
                            )
                        ))
                )
            ));
            $inputFilter->add(array(
                'name' => 'city',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'nationality',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'identificationType',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'identificationNumber',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'identificationExpiryDate',
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
                'name' => 'securityQuestion',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'securityAnswer',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'email',
                'required' => true,
                'validators' => array(
                    array('name' => 'EmailAddress',
                    ),
                    array('name' => 'DoctrineModule\Validator\UniqueObject',
                        'options' => array(
                            'use_context' => true,
                            'object_manager' => $query->entityManager,
                            'object_repository' => $query->entityRepository,
                            'fields' => array('email'),
                            'messages' => array(UniqueObject::ERROR_OBJECT_NOT_UNIQUE => "This email address is already in use")
                        )
                    ),
                ),
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));
            $inputFilter->add(array(
                'name' => 'confirmEmail',
                'required' => true,
                'validators' => array(
                    array('name' => 'EmailAddress',
                    ),
                )
            ));

            $inputFilter->add(array(
                'name' => 'roles',
                'required' => false,
            ));

            $inputFilter->add(array(
                'name' => 'privacyStatement',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Identical',
                        'options' => array(
                            'token' => (string) Status::STATUS_ACTIVE,
                            'messages' => array(
                                Identical::NOT_SAME => 'You must agree to the privacy statement',
                            ),
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
