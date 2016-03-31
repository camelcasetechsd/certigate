<?php

namespace Courses\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * 
 * @ORM\Entity
 * @ORM\Table(name="question")
 * @Gedmo\Loggable
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property int $questionTitle
 * @property int $status
 * 

 * 
 * @package courses
 * @subpackage entity
 */
class Question
{

    /**
     *
     * @var Array translated properties
     */
    protected $translatedProperties = [
        'questionTitle' => [
            'en_US' => 'questionTitle',
            'ar_AR' => 'questionTitleAr'
        ],
    ];

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
     * @ORM\Column(type="string")
     * @var string
     */
    public $questionTitleAr;

    /**
     * @ORM\ManyToOne(targetEntity="Evaluation", inversedBy="questions")
     * @ORM\JoinColumn(name="evaluation_id", referencedColumnName="id")
     */
    public $evaluation;

    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\Vote", mappedBy="question")
     */
    public $votes;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer")
     * @var int
     */
    public $status;

    function getId()
    {
        return $this->id;
    }

    /**
     * Get Question Title
     * 
     * @return string
     */
    function getQuestionTitle()
    {
        return $this->questionTitle;
    }

    /**
     * Set Question Title 
     * 
     * @param type $questionTitle
     * @return \Courses\Entity\Question
     */
    function setQuestionTitle($questionTitle)
    {
        $this->questionTitle = $questionTitle;
        return $this;
    }

    /**
     * Get Question Title in Arabic
     * 
     * @return string
     */
    function getQuestionTitleAr()
    {
        return $this->questionTitleAr;
    }

    /**
     * Set Question Title in Arabic
     * 
     * @param type $questionTitleAr
     * @return \Courses\Entity\Question
     */
    function setQuestionTitleAr($questionTitleAr)
    {
        $this->questionTitleAr = $questionTitleAr;
        return $this;
    }
    /**
     * Assign question to evalution
     * 
     * @param type $evaluation
     * @return \Courses\Entity\Question
     */
    function setToEvaluation($evaluation)
    {
        $this->evaluation = $evaluation;
        return $this;
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
     * @return Question
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
        $this->setQuestionTitle($data["questionTitle"])
                ->setStatus($data["status"]);
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

            $inputFilter->add(array(
                'name' => 'questionTitle',
                'required' => true,
                    )
            );

            $inputFilter->add(array(
                'name' => 'questionTitleAr',
                'required' => true,
                    )
            );

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
