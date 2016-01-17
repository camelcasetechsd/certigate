<?php

namespace Courses\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

/**
 * Menu Entity
 * @ORM\Entity
 * @ORM\Table(name="course")
 * 
 * @package courses
 * @subpackage entity
 */
class Course 
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
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    public $name;
    
    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $startDate;
    
    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $endDate;
    
    /**
     *
     * @ORM\Column(type="integer")
     * @var int
     */
    public $capacity;
    
    /**
     *
     * @ORM\Column(type="integer")
     * @var int
     */
    public $studentsNo;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Users\Entity\User")
     * @ORM\JoinColumn(name="atp_id", referencedColumnName="id")
     * @var Users\Entity\User
     */
    public $atp;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Users\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var Users\Entity\User
     */
    public $ai;
    
    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $brief;
    
    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $time;
    
    /**
     *
     * @ORM\Column(type="integer")
     * @var int
     */
    public $duration;

    /**
     *
     * @ORM\Column(type="integer")
     * @var int
     */
    public $status;
    
    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $created;
    
    /**
     *
     * @ORM\Column(type="date" , nullable=true)
     * @var \DateTime
     */
    public $modified = null;
    
    /**
     * Get id
     * 
     * 
     * @access public
     * @return int id
     */
    public function getId() {
        return $this->id;
    }  
    
    /**
     * Get Name
     * 
     * 
     * @access public
     * @return string name
     */
    public function getName() {
        return $this->name;
    }    

    /**
     * Set name
     * 
     * 
     * @access public
     * @param string $name
     * @return Course
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
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
     * Set status
     * 
     * 
     * @access public
     * @param int $status
     * @return Course
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }
    
    /**
     * Get created
     * 
     * 
     * @access public
     * @return \DateTime created
     */
    public function getCreated() {
        return $this->created;
    }
    
    /**
     * Set created
     * 
     * @ORM\PrePersist
     * @access public
     * @return Course
     */
    public function setCreated() {
        $this->created = new \DateTime();
        return $this;
    }
    
    /**
     * Get modified
     * 
     * 
     * @access public
     * @return \DateTime modified
     */
    public function getModified() {
        return $this->modified;
    }
    
    /**
     * Set modified
     * 
     * @ORM\PreUpdate
     * @access public
     * @return Course
     */
    public function setModified() {
        $this->modified = new \DateTime();
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
        if(array_key_exists('title', $data)){
            $this->setTitle($data["title"]);
        }
        if(array_key_exists('status', $data)){
            $this->setStatus($data["status"]);
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
     * @param Utilities\Service\Query\Query $query
     * @return InputFilter validation constraints
     */
    public function getInputFilter($query) {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'title',
                'required' => true,
                'validators' => array(
                    array('name' => 'DoctrineModule\Validator\UniqueObject',
                        'options' => array(
                            'use_context'   => true,
                            'object_manager' => $query->entityManager,
                            'object_repository' => $query->entityRepository,
                            'fields' => array('title')
                        )
                    ),
                )
            ));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
