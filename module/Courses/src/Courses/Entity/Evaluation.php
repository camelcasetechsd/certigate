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
 * @property int $Approved
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
     * not approved by admin
     */
    const NOT_APPROVED = 0;

    /**
     * approved by admin
     */
    const APPROVED = 1;

    /**
     * approved by admin
     */
    const DECLINED = 2;

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
    public $isApproved;

    /**
     * @ORM\OneToOne(targetEntity="Course", inversedBy="evaluation")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    public $course;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="evaluation", cascade={"remove"})
     */
    public $questions;

    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\Vote", mappedBy="evaluation")
     */
    public $votes;

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

    function isApproved()
    {
        if ($this->isApproved == self::APPROVED) {
            return TRUE;
        }
        return False;
    }

    function setIsApproved()
    {
        $this->isApproved = self::APPROVED;
    }

    function setIsDeclined()
    {
        $this->isApproved = self::DECLINED;
    }

    function setIsNotApproved()
    {
        $this->isApproved = self::NOT_APPROVED;
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
