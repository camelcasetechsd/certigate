<?php

namespace Courses\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Utilities\Service\Time;
use Utilities\Service\String;

/**
 * CourseEvent Entity
 * @ORM\Entity
 * @ORM\Table(name="course_event")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 *  
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property Courses\Entity\Course $course
 * @property \DateTime $startDate
 * @property \DateTime $endDate
 * @property int $capacity
 * @property int $studentsNo
 * @property Organizations\Entity\Organization $atp
 * @property Users\Entity\User $ai
 * @property int $status
 * @property Doctrine\Common\Collections\ArrayCollection $users
 * @property Doctrine\Common\Collections\ArrayCollection $votes
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package courses
 * @subpackage entity
 */
class CourseEvent
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
     * @ORM\ManyToOne(targetEntity="Courses\Entity\Course", inversedBy="courseEvents")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * @var Courses\Entity\Course
     */
    public $course;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $startDate;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $endDate;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer")
     * @var int
     */
    public $capacity;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer")
     * @var int
     */
    public $studentsNo;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Organizations\Entity\Organization")
     * @ORM\JoinColumn(name="atp_id", referencedColumnName="id")
     * @var Organizations\Entity\Organization
     */
    public $atp;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Users\Entity\User")
     * @ORM\JoinColumn(name="ai_id", referencedColumnName="id")
     * @var Users\Entity\User
     */
    public $ai;

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
     * @ORM\ManyToMany(targetEntity="Users\Entity\User", inversedBy="courseEvents")
     * @ORM\JoinTable(name="course_events_users",
     *      joinColumns={@ORM\JoinColumn(name="course_event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    public $users;

    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\Vote", mappedBy="courseEvent")
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    public $votes;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer")
     * @var int
     */
    public $status;
    
    /**
     * Prepare entity
     * 
     * 
     * @access public
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

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
     * Get Course
     * 
     * 
     * @access public
     * @return Course course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set Course
     * 
     * 
     * @access public
     * @param Course $course
     * @return CourseEvent
     */
    public function setCourse($course)
    {
        $this->course = $course;
        return $this;
    }
    
    /**
     * Get Start Date
     * 
     * 
     * @access public
     * @return \DateTime startDate
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set Start Date
     * 
     * 
     * @access public
     * @param \DateTime $startDate
     * @return CourseEvent
     */
    public function setStartDate($startDate)
    {
        if (!is_object($startDate)) {
            $startDate = \DateTime::createFromFormat(Time::DATE_FORMAT, $startDate);
        }
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * Get End Date
     * 
     * 
     * @access public
     * @return \DateTime endDate
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set End Date
     * 
     * 
     * @access public
     * @param \DateTime $endDate
     * @return CourseEvent
     */
    public function setEndDate($endDate)
    {
        if (!is_object($endDate)) {
            $endDate = \DateTime::createFromFormat(Time::DATE_FORMAT, $endDate);
        }
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Get capacity
     * 
     * 
     * @access public
     * @return int capacity
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Set capacity
     * 
     * 
     * @access public
     * @param int $capacity
     * @return CourseEvent
     */
    public function setCapacity($capacity)
    {
        $this->capacity = (int) $capacity;
        return $this;
    }

    /**
     * Get Students No
     * 
     * 
     * @access public
     * @return int studentsNo
     */
    public function getStudentsNo()
    {
        return $this->studentsNo;
    }

    /**
     * Set Students No
     * 
     * 
     * @access public
     * @param int $studentsNo
     * @return CourseEvent
     */
    public function setStudentsNo($studentsNo)
    {
        $this->studentsNo = (int) $studentsNo;
        return $this;
    }

    /**
     * Get Atp
     * 
     * 
     * @access public
     * @return Organizations\Entity\Organization atp
     */
    public function getAtp()
    {
        return $this->atp;
    }

    /**
     * Set Atp
     * 
     * 
     * @access public
     * @param Organizations\Entity\Organization $atp
     * @return CourseEvent
     */
    public function setAtp($atp)
    {
        $this->atp = $atp;
        return $this;
    }

    /**
     * Get Ai
     * 
     * 
     * @access public
     * @return Users\Entity\User Ai
     */
    public function getAi()
    {
        return $this->ai;
    }

    /**
     * Set Ai
     * 
     * 
     * @access public
     * @param Users\Entity\User $ai
     * @return CourseEvent
     */
    public function setAi($ai)
    {
        $this->ai = $ai;
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
     * @return CourseEvent
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
     * @return CourseEvent
     */
    public function setModified()
    {
        $this->modified = new \DateTime();
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
     * @return CourseEvent
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    
    /**
     * Get Users
     * 
     * 
     * @access public
     * @return ArrayCollection users
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add Users
     * 
     * 
     * @access public
     * @param Users\Entity\User $user
     * @return CourseEvent
     */
    public function addUser($user)
    {
        $this->users[] = $user;
        return $this;
    }

    /**
     * Set Users
     * 
     * 
     * @access public
     * @param ArrayCollection $users
     * @return CourseEvent
     */
    public function setUsers($users)
    {
        $this->users = $users;
        return $this;
    }
    
    /**
     * Get Votes
     * 
     * 
     * @access public
     * @return ArrayCollection votes
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set Votes
     * 
     * 
     * @access public
     * @param ArrayCollection $votes
     * @return CourseEvent
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;
        return $this;
    }

    /**
     * Get detailed name
     * 
     * @access public
     * @return string detailed name
     */
    public function getDetailedName()
    {
        $detailedName = $this->getCourse()->getName() . String::TEXT_SEPARATOR . "Start: " . $this->getStartDate()->format(Time::DATE_FORMAT)
                            . " - End: " . $this->getEndDate()->format(Time::DATE_FORMAT)
                            . " - ATP: " . $this->getAtp()->getCommercialName();
        return $detailedName;
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
        if (array_key_exists('course', $data)) {
            $this->setCourse($data["course"]);
        }
        $this->setAi($data["ai"])
                ->setAtp($data["atp"])
                ->setCapacity($data["capacity"])
                ->setEndDate($data["endDate"])
                ->setStartDate($data["startDate"])
                ->setStudentsNo($data["studentsNo"])
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
                'name' => 'course',
                'required' => true
            ));

            $inputFilter->add(array(
                'name' => 'startDate',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'endDate',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'capacity',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'studentsNo',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'atp',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'ai',
                'required' => true,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
