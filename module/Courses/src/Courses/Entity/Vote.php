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
 * @ORM\Table(name="vote")
 * @Gedmo\Loggable
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property int $vote
 * 

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
     * any one who can vote for course 
     * 
     * @ORM\ManyToOne(targetEntity="Users\Entity\User", inversedBy="votes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    public $user;

    /**
     * @ORM\OneToOne(targetEntity="Courses\Entity\Question", inversedBy="votes")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    public $question;

    function getId()
    {
        return $this->id;
    }

    function getVote()
    {
        return $this->vote;
    }

    function getEvaluation()
    {
        return $this->evaluation;
    }

    function getUser()
    {
        return $this->user;
    }

    function getQuestion()
    {
        return $this->question;
    }

    function setVote($vote)
    {
        $this->vote = $vote;
    }

    function setEvaluation($evaluation)
    {
        $this->evaluation = $evaluation;
    }

    function setUser($user)
    {
        $this->user = $user;
    }

    function setQuestion($question)
    {
        $this->question = $question;
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
        $this->vote($data);
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

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
