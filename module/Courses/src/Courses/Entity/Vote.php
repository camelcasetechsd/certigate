<?php

namespace Courses\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * 
 * @ORM\Entity(repositoryClass="Courses\Entity\VoteRepository")
 * @ORM\Table(name="vote")
 * @Gedmo\Loggable
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property int $vote
 * @property Courses\Entity\CourseEvent $courseEvent
 * @property Courses\Entity\Evaluation $evaluation
 * @property Users\Entity\User $user
 * @property Courses\Entity\Question $question
 * 
 * @package courses
 * @subpackage entity
 */
class Vote
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
     * @Gedmo\Versioned
     * @ORM\Column(type="integer")
     * @var int
     */
    public $vote;

    /**
     * @ORM\ManyToOne(targetEntity="Courses\Entity\Evaluation", inversedBy="votes")
     * @ORM\JoinColumn(name="evaluation_id", referencedColumnName="id")
     */
    public $evaluation;

    /**
     * @ORM\ManyToOne(targetEntity="Courses\Entity\CourseEvent", inversedBy="votes")
     * @ORM\JoinColumn(name="course_event_id", referencedColumnName="id")
     */
    public $courseEvent;

    /**
     * any one who can vote for course 
     * 
     * @ORM\ManyToOne(targetEntity="Users\Entity\User", inversedBy="votes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    public $user;

    /**
     * @ORM\ManyToOne(targetEntity="Courses\Entity\Question", inversedBy="votes")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    public $question;

    /**
     * Get id
     * 
     * @access public
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get vote
     * 
     * @access public
     * @return int
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * Get evaluation
     * 
     * @access public
     * @return Courses\Entity\Evaluation
     */
    public function getEvaluation()
    {
        return $this->evaluation;
    }

    /**
     * Get user
     * 
     * @access public
     * @return Users\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Get Course Event
     * 
     * @access public
     * @return Courses\Entity\CourseEvent
     */
    public function getCourseEvent()
    {
        return $this->courseEvent;
    }

    /**
     * Get Question
     * 
     * @access public
     * @return Courses\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set Vote
     * 
     * @access public
     * @param int $vote
     * @return Courses\Entity\Vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;
        return $this;
    }

    /**
     * Set evaluation
     * 
     * @access public
     * @param Courses\Entity\Evaluation $evaluation
     * @return Courses\Entity\Vote
     */
    public function setEvaluation($evaluation)
    {
        $this->evaluation = $evaluation;
        return $this;
    }

    /**
     * Set user
     * 
     * @access public
     * @param Users\Entity\User $user
     * @return Courses\Entity\Vote
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
    
    /**
     * Set Course Event
     * 
     * @access public
     * @param Courses\Entity\CourseEvent $courseEvent
     * @return Courses\Entity\Vote
     */
    public function setCourseEvent($courseEvent)
    {
        $this->courseEvent = $courseEvent;
        return $this;
    }

    /**
     * Set Question
     * 
     * @access public
     * @param Courses\Entity\Question $question
     * @return Courses\Entity\Vote
     */
    public function setQuestion($question)
    {
        $this->question = $question;
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
        $this
                ->setCourseEvent($data["courseEvent"])
                ->setEvaluation($data["evaluation"])
                ->setQuestion($data["question"])
                ->setUser($data["user"])
                ->setVote($data["vote"])
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
     * @return InputFilter validation constraints
     */
    public function getInputFilter($query)
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'vote',
                'required' => true,
                    )
            );
            $inputFilter->add(array(
                'name' => 'courseEvent',
                'required' => true,
                    )
            );

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
