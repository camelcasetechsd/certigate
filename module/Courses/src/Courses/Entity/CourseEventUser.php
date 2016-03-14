<?php

namespace Courses\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Utilities\Service\Random;

/**
 * CourseEventUser Entity
 * @ORM\Entity
 * @ORM\Table(name="course_events_users")
 * @ORM\HasLifecycleCallbacks
 *  
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property Courses\Entity\CourseEvent $courseEvent
 * @property Users\Entity\User $user
 * @property string $token
 * @property int $status
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package courses
 * @subpackage entity
 */
class CourseEventUser
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
     * @ORM\ManyToOne(targetEntity="Courses\Entity\CourseEvent", inversedBy="courseEventUsers")
     * @ORM\JoinColumn(name="course_event_id", referencedColumnName="id")
     * @var Courses\Entity\CourseEvent
     */
    public $courseEvent;

    /**
     * @ORM\ManyToOne(targetEntity="Users\Entity\User", inversedBy="courseEventUsers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var Users\Entity\User
     */
    public $user;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    public $token;
    
    /**
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get CourseEvent
     * 
     * 
     * @access public
     * @return CourseEvent courseEvent
     */
    public function getCourseEvent()
    {
        return $this->courseEvent;
    }

    /**
     * Set CourseEvent
     * 
     * 
     * @access public
     * @param CourseEvent $courseEvent
     * @return CourseEventUser
     */
    public function setCourseEvent($courseEvent)
    {
        $this->courseEvent = $courseEvent;
        return $this;
    }

    /**
     * Get User
     * 
     * 
     * @access public
     * @return Users\Entity\User User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set User
     * 
     * 
     * @access public
     * @param Users\Entity\User $user
     * @return CourseEventUser
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get token
     * 
     * 
     * @access public
     * @return string token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set token
     * 
     * 
     * @access public
     * @param string $token ,default is null
     * @return CourseEventUser
     */
    public function setToken($token = null)
    {
        if(is_null($token)){
            $random = new Random();
            $token = $random->getRandomUniqueName();
        }
        $this->token = $token;
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
     * @return CourseEventUser
     */
    public function setStatus($status)
    {
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
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created
     * 
     * @ORM\PrePersist
     * @access public
     * @return CourseEventUser
     */
    public function setCreated()
    {
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
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set modified
     * 
     * @ORM\PreUpdate
     * @access public
     * @return CourseEventUser
     */
    public function setModified()
    {
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
        if (array_key_exists('status', $data)) {
            $this->setStatus($data["status"]);
        }
        $token = null;
        if (array_key_exists('token', $data)) {
            $token = $data["token"];
        }
        $this->setToken($token);
        $this->setCourseEvent($data["courseEvent"])
                ->setUser($data["user"])
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
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'courseEvent',
                'required' => true
            ));

            $inputFilter->add(array(
                'name' => 'user',
                'required' => true,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
