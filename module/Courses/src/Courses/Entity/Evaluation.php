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
 * @property int $isTemplate
 * @property int $status
 * 

 * 
 * @package courses
 * @subpackage entity
 */
class Evaluation
{

    /**
     * question created by user
     */
    const NOT_TEMPLATE = 0;

    /**
     * question created by admin
     */
    const IS_TEMPLATE = 1;

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
     * @ORM\Column(type="integer")
     * @var int
     */
    public $isTemplate;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer")
     * @var int
     */
    public $status;

    /**
     * @ORM\OneToOne(targetEntity="Course", inversedBy="evaluation")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    public $course;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="evaluation", cascade={"remove","persist"})
     */
    public $questions;

    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\Vote", mappedBy="evaluation")
     */
    public $votes;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="float")
     * @var float
     */
    public $percentage;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    function getId()
    {
        return $this->id;
    }

    function getCourse()
    {
        return $this->course;
    }

    function getVotes()
    {
        return $this->votes;
    }

    function getPercentage()
    {
        return $this->percentage;
    }

    function setIsTemplate()
    {
        $this->isTemplate = self::IS_TEMPLATE;
    }

    function setIsUserEval()
    {
        $this->isTemplate = self::NOT_TEMPLATE;
    }

    function isTemplate()
    {
        if ($this->isTemplate == self::IS_TEMPLATE) {
            return TRUE;
        }
        return False;
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
     * Set status
     * 
     * 
     * @access public
     * @param int $status
     * @return Evaluation
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    
    function setCourse($course)
    {
        $this->course = $course;
    }

    function getQuestions()
    {
        return $this->questions;
    }

    function setQuestions($questions)
    {
        $this->questions = $questions;
    }

    public function addQuestion(Question $question)
    {

        $this->questions[] = $question;
    }

    function setPercentage($percentage)
    {
        $this->percentage = $percentage;
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
