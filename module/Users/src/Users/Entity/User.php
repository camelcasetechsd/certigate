<?php

namespace Users\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Regex;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User Entity
 * @ORM\Entity
 * @ORM\Table(name="user")
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
 * @property string $photo
 * @property string $maritalStatus
 * @property string $description
 * @property array $roles
 * @property int $status
 * 
 * @package users
 * @subpackage entity
 */
class User {
    
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
     * @ORM\Column(type="string" , length = 11 )
     * @var string
     */
    public $mobile;

    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $dateOfBirth;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $photo;

    /**
     *
     * @ORM\Column(type="string" )
     * @var string
     */
    public $maritalStatus;

    /**
     *
     * @ORM\Column(type="string" , length = 1024 )
     * @var string
     */
    public $description;

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
    public $status;

    /**
     * hash password
     * 
     * 
     * @access public
     * @param string $password
     * @return string hashed password
     */
    static public function hashPassword($password) {
        if (function_exists("password_hash")) {
            return password_hash($password, PASSWORD_BCRYPT);
        } else {
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
    static public function verifyPassword($givenPassword, $savedPassword) {
        if (function_exists('password_verify')) {
            return password_verify($givenPassword, $savedPassword);
        } else {
            return crypt($givenPassword, $savedPassword) == $savedPassword;
        }
    }

    /**
     * Prepare user entity
     * 
     * 
     * @access public
     */
    public function __construct() {
        $this->roles = new ArrayCollection();
    }
    
    /**
     * Get dateOfBirth
     * 
     * 
     * @access public
     * @return \DateTime dateOfBirth
     */
    public function getDateOfBirth() {
        return $this->dateOfBirth;
    }

    /**
     * Get description
     * 
     * 
     * @access public
     * @return string description
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Get maritalStatus
     * 
     * 
     * @access public
     * @return string maritalStatus
     */
    public function getMaritalStatus() {
        return $this->maritalStatus;
    }

    /**
     * Get mobile
     * 
     * 
     * @access public
     * @return string mobile
     */
    public function getMobile() {
        return $this->mobile;
    }

    /**
     * Get firstName
     * 
     * 
     * @access public
     * @return string firstName
     */
    public function getFirstName() {
        return $this->firstName;
    }
    
    /**
     * Get middleName
     * 
     * 
     * @access public
     * @return string middleName
     */
    public function getMiddleName() {
        return $this->middleName;
    }
    
    /**
     * Get lastName
     * 
     * 
     * @access public
     * @return string lastName
     */
    public function getLastName() {
        return $this->lastName;
    }
    
    /**
     * Get country
     * 
     * 
     * @access public
     * @return string country
     */
    public function getCountry() {
        return $this->country;
    }
    
    /**
     * Get language
     * 
     * 
     * @access public
     * @return string language
     */
    public function getLanguage() {
        return $this->language;
    }
    
    /**
     * Get fullName
     * 
     * 
     * @access public
     * @return string fullName
     */
    public function getFullName() {
        $fullName = $this->getFirstName();
        if(! empty($this->getMiddleName())){
            $fullName .= " ".$this->getMiddleName();
        }
        $fullName .= " ".$this->getLastName();
        return $fullName;
    }

    /**
     * Get password
     * 
     * 
     * @access public
     * @return string password
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Get photo
     * 
     * 
     * @access public
     * @return string photo
     */
    public function getPhoto() {
        return $this->photo;
    }

    /**
     * Get roles
     * 
     * 
     * @access public
     * @return ArrayCollection Users\Entity\Role roles
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * Get status
     * 
     * 
     * @access public
     * @return int status
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Get username
     * 
     * 
     * @access public
     * @return string username
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set dateOfBirth
     * 
     * 
     * @access public
     * @param \DateTime $dateOfBirth
     * @return User current entity
     */
    public function setDateOfBirth($dateOfBirth) {
        $this->dateOfBirth = new \DateTime($dateOfBirth);
        return $this;
    }

    /**
     * Set description
     * 
     * 
     * @access public
     * @param string $description
     * @return User current entity
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * Set maritalStatus
     * 
     * 
     * @access public
     * @param string $maritalStatus
     * @return User current entity
     */
    public function setMaritalStatus($maritalStatus) {
        $this->maritalStatus = $maritalStatus;
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
    public function setMobile($mobile) {
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
    public function setFirstName($firstName) {
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
    public function setMiddleName($middleName) {
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
    public function setLastName($lastName) {
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
    public function setCountry($country) {
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
    public function setLanguage($language) {
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
    public function setPassword($password) {
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
    public function setPhoto($photo) {
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
    public function addRole($role) {
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
    public function setRoles($roles) {
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
    public function setStatus($status) {
        $this->status = $status;
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
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    /**
     * Convert the object to an array.
     * 
     * 
     * @access public
     * @return array current entity properties
     */
    public function getArrayCopy() {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     * 
     * 
     * @access public
     * @param array $data ,default is empty array
     */
    public function exchangeArray($data = array()) {
        if(array_key_exists('roles', $data)){
            $this->setRoles($data["roles"]);
        }
        if(array_key_exists('status', $data)){
            $this->setStatus($data["status"]);
        }
        if(array_key_exists('photo', $data) && is_string($data['photo'])){
            $this->setPhoto($data["photo"]);
        }
        $this->setDateOfBirth($data["dateOfBirth"])
                ->setDescription($data["description"])
                ->setMaritalStatus($data["maritalStatus"])
                ->setMobile($data["mobile"])
                ->setFirstName($data["firstName"])
                ->setLastName($data["lastName"])
                ->setMiddleName($data["middleName"])
                ->setCountry($data["country"])
                ->setLanguage($data["language"])
                ->setPassword($data["password"])
                ->setUsername($data["username"]);
    }

    /**
     * setting inputFilter is forbidden
     * 
     * 
     * @access public
     * @param InputFilterInterface $inputFilter
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter) {
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
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'username',
                'required' => true,
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
                        'name' => 'Digits',
                    ),
                    array(
                        'name' => 'regex',
                        'options' => array(
                            'pattern' => '/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/',
                            'messages' => array(Regex::NOT_MATCH => 'This is not a mobile number!')
                        )
                    )
                )
            ));
            $inputFilter->add(array(
                'name' => 'dateOfBirth',
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
                'name' => 'description',
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                ),
            ));

            $inputFilter->add(array(
                'name' => 'photo',
                'required' => true,
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
                'name' => 'roles',
                'required' => true,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
