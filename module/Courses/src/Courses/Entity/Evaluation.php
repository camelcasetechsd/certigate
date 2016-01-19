<?php

namespace Courses\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Course Entity
 * @ORM\Entity
 * @ORM\Table(name="evaluation")
 * @Gedmo\Loggable

 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property string $questionTitle
 * @property int $isAdmin
 * 

 * 
 * @package courses
 * @subpackage entity
 */
class Evaluation
{
    /*
     * question created by user
     */

    const USER_CREATED = 0;
    /*
     * question created by admin
     */
    const ADMIN_CREATED = 1;

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
     * @Gedmo\Versioned
     * @ORM\Column(type="string")
     * @var string
     */
    public $questionTitle;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer")
     * @var int
     */
    public $isAdmin;

    /**
     * 
     * @ORM\ManyToMany(targetEntity="Courses\Entity\Course", mappedBy="evaluations")
     */
    public $courses;

    public function __construct()
    {
        $this->courses =  new ArrayCollection();
    }

    function getId()
    {
        return $this->id;
    }

    function getQuestionTitle()
    {
        return $this->questionTitle;
    }

    function getIsAdmin()
    {
        return $this->isAdmin;
    }

    function setQuestionTitle($questionTitle)
    {
        $this->questionTitle = $questionTitle;
    }

    function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * Add Courses
     * 
     * 
     * @access public
     * @param Courses\Entity\Course $courses
     * @return Course
     */
    public function addCourse($course)
    {      
        $this->courses = $course;
        return $this;
    }

    /**
     * Set Courses
     * 
     * 
     * @access public
     * @param ArrayCollection $courses
     * @return Course
     */
    public function setCourses($courses)
    {
        $this->courses[] = $courses;
        return $this;
    }

    /**
     * Get evaluations
     * 
     * 
     * @access public
     * @return ArrayCollection evaluations
     */
    public function getCourses()
    {
        return $this->courses;
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
        $this->setQuestionTitle($data['questionTitle']);
        $this->setIsAdmin($data['isAdmin']);
//        $this->addCourses($data['courses']);
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
                'name' => 'questionTitle',
                'required' => true
            ));
            $inputFilter->add(array(
                'name' => 'isAdmin',
                'required' => true
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
