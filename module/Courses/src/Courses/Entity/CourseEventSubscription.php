<?php

namespace Courses\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use DoctrineModule\Validator\UniqueObject;

/**
 * CourseEventSubscription Entity
 * @ORM\Entity(repositoryClass="Courses\Entity\CourseEventSubscriptionRepository")
 * @ORM\Table(name="course_event_subscription",uniqueConstraints={@ORM\UniqueConstraint(name="user_course_event_idx", columns={"user_id","course_event_id"})})
 * @ORM\HasLifecycleCallbacks
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property Users\Entity\User $user
 * @property Courses\Entity\CourseEvent $courseEvent
 * @property \DateTime $lastNotified
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package courses
 * @subpackage entity
 */
class CourseEventSubscription {

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
     * @ORM\ManyToOne(targetEntity="Users\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var Users\Entity\User
     */
    public $user;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Courses\Entity\CourseEvent")
     * @ORM\JoinColumn(name="course_event_id", referencedColumnName="id")
     * @var Courses\Entity\CourseEvent
     */
    public $courseEvent;
    
    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $created;
    
    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $lastNotified;
    
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
     * Get user
     * 
     * 
     * @access public
     * @return Users\Entity\User user
     */
    public function getUser() {
        return $this->user;
    }    

    /**
     * Set user
     * 
     * 
     * @access public
     * @param Users\Entity\User $user
     * @return CourseEventSubscription current entity
     */
    public function setUser($user) {
        $this->user = $user;
        return $this;
    }
    
    /**
     * Get courseEvent
     * 
     * 
     * @access public
     * @return Courses\Entity\CourseEvent courseEvent
     */
    public function getCourseEvent() {
        return $this->courseEvent;
    }    

    /**
     * Set courseEvent
     * 
     * 
     * @access public
     * @param Courses\Entity\CourseEvent $courseEvent
     * @return CourseEventSubscription current entity
     */
    public function setCourseEvent($courseEvent) {
        $this->courseEvent = $courseEvent;
        return $this;
    }
    
    /**
     * Get lastNotified
     * 
     * 
     * @access public
     * @return \DateTime lastNotified
     */
    public function getLastNotified() {
        return $this->lastNotified;
    }
    
    /**
     * Set lastNotified
     * 
     * @ORM\PrePersist
     * @access public
     * @return CourseEventSubscription current entity
     */
    public function setLastNotified() {
        $this->lastNotified = new \DateTime();
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
     * @return CourseEventSubscription current entity
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
     * @return CourseEventSubscription current entity
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
        if(array_key_exists('user', $data)){
            $this->setUser($data["user"]);
        }
        if(array_key_exists('courseEvent', $data)){
            $this->setCourseEvent($data["courseEvent"]);
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
                'name' => 'user',
                'required' => true,
                'validators' => array(
                    array('name' => 'Utilities\Service\Validator\UniqueObject',
                        'options' => array(
                            'use_context'   => true,
                            'object_manager' => $query->entityManager,
                            'object_repository' => $query->entityRepository,
                            'fields' => array('user', 'courseEvent'),
                            'messages' => array(UniqueObject::ERROR_OBJECT_NOT_UNIQUE => "The current user is already subscribed for that courseEvent!")
                        )
                    ),
                )
            ));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
