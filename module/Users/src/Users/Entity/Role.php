<?php

namespace Users\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

/**
 * Role Entity
 * @ORM\Entity
 * @ORM\Table(name="role")
 * 
 * 
 * @property int $id
 * @property string $name
 * 
 * @package users
 * @subpackage entity
 */
class Role
{

    /**
     * User role
     */
    const USER_ROLE = "User";
    /**
     * Admin role
     */
    const ADMIN_ROLE = "Admin";
    /**
     * Student role
     */
    const STUDENT_ROLE = "Student";
    /**
     * Proctor role
     */
    const PROCTOR_ROLE = "Proctor";
    /**
     * Instructor role
     */
    const INSTRUCTOR_ROLE = "Instructor";
    /**
     * Test Center Administrator role
     */
    const TEST_CENTER_ADMIN_ROLE = "Test Center Administrator";
    /**
     * Training Manager role
     */
    const TRAINING_MANAGER_ROLE = "Training Manager";
    
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
     * Gets the value of id.
     *
     * @return int
     * @access public
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param int $id the id
     *
     * @return self
     * @access public
     */
    public function setId( $id )
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of name.
     *
     * @return string
     * @access public
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param string $name the name
     *
     * @return self
     * @access public
     */
    public function setName( $name )
    {
        $this->name = $name;

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
        return get_object_vars( $this );
    }

    /**
     * Populate from an array.
     * 
     * 
     * @access public
     * @param array $data ,default is empty array
     */
    public function exchangeArray( $data = array() )
    {
        
        $this->setName( $data["name"] );
    }

    /**
     * setting inputFilter is forbidden
     * 
     * 
     * @access public
     * @param InputFilterInterface $inputFilter
     * @throws \Exception
     */
    public function setInputFilter( InputFilterInterface $inputFilter )
    {
        throw new \Exception( "Not used" );
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


            $inputFilter->add( array(
                'name' => 'name',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'DoctrineModule\Validator\UniqueObject',
                        'options' => array(
                            'use_context' => true,
                            'object_manager' => $query->entityManager,
                            'object_repository' => $query->entityRepository,
                            'fields' => array('name')
                        )
                    ),
                )
            ) );
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
