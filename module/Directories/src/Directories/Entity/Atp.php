<?php

namespace Directories\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Regex;


/**
 * ATP Entity
 * @ORM\Entity
 * @ORM\Table(name="atp")
 * 
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $contanctName
 * @property string $email
 * @property string $telephone
 * @property array $address
 * @property \DateTime $joinDate
 * @property \DateTime $createDate
 * 
 * @package directories
 * @subpackage entity
 */
class Atp
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
    public $name;

    /**
     *
     * @ORM\Column(type="string" , length = 1024 )
     * @var string
     */
    public $description;

    /**
     *
     * @ORM\Column(type="string" , length = 1024 )
     * @var string
     */
    public $contactName;

    /**
     *
     * @ORM\Column(type="string" , length = 1024 )
     * @var string
     */
    public $email;

    /**
     *
     * @ORM\Column(type="string" , length = 1024 )
     * @var string
     */
    public $telephone;

    /**
     *
     * @ORM\Column(type="string" , length = 1024 )
     * @var string
     */
    public $address;

    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $joinDate;

    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $createDate;

    /**
     * Get name
     * 
     * 
     * @access public
     * @return string name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get description
     * 
     * 
     * @access public
     * @return string description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get telephone
     * 
     * 
     * @access public
     * @return string telephone
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Get contactName
     * 
     * 
     * @access public
     * @return string contactName
     */
    public function getContactName()
    {
        return $this->contactName;
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
     * Get address
     * 
     * 
     * @access public
     * @return string address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get joinDate
     * 
     * 
     * @access public
     * @return \DateTime joinDate
     */
    public function getJoinDate()
    {
        return $this->joinDate;
    }

    /**
     * Get createDate
     * 
     * 
     * @access public
     * @return \DateTime createDate
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /*
     * Setters
     */

    /**
     * Set name
     * 
     * 
     * @access public
     * @param string $name
     * @return ATP current entity
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set contactName
     * 
     * 
     * @access public
     * @param string $contactName
     * @return ATP current entity
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;
        return $this;
    }

    /**
     * Set address
     * 
     * 
     * @access public
     * @param string $address
     * @return ATP current entity
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Set email
     * 
     * 
     * @access public
     * @param string $email
     * @return ATP current entity
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Set telephone
     * 
     * 
     * @access public
     * @param string $telephone
     * @return ATP current entity
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     * Set description
     * 
     * 
     * @access public
     * @param string $description
     * @return ATP current entity
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set joinDate
     * 
     * 
     * @access public
     * @param \DateTime $joinDate
     * @return ATP current entity
     */
    public function setJoinDate($joinDate)
    {
        $this->joinDate = $joinDate;
        return $this;
    }

    /**
     * Set createDate
     * 
     * 
     * @access public
     * @param \DateTime $createDate
     * @return ATP current entity
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
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
        $this->setName($data["dateOfBirth"])
                ->setDescription($data["dateOfBirth"])
                ->setContactName($data["dateOfBirth"])
                ->setEmail($data["dateOfBirth"])
                ->setTelephone($data["dateOfBirth"])
                ->setAddress($data["dateOfBirth"])
                ->setJoinDate($data["dateOfBirth"])
                ->setCreateDate($data["dateOfBirth"]);
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
                'name' => 'name',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
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
                'name' => 'telephone',
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
                            'messages' => array(Regex::NOT_MATCH => 'This is not a telephone number!')
                        )
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'contactName',
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'email',
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                ),
            ));

            $inputFilter->add(array(
                'name' => 'Address',
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    )
                ),
            ));

            $inputFilter->add(array(
                'name' => 'joinDate',
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
                'name' => 'createDate',
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

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
