<?php

namespace Courses\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Utilities\Service\Time;

/**
 * Course Entity
 * @ORM\Entity
 * @ORM\Table(name="course")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 *  
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property string $name
 * @property \DateTime $startDate
 * @property \DateTime $endDate
 * @property int $capacity
 * @property int $studentsNo
 * @property Organizations\Entity\Organization $atp
 * @property Users\Entity\User $ai
 * @property Doctrine\Common\Collections\ArrayCollection $resources
 * @property Doctrine\Common\Collections\ArrayCollection $outlines
 * @property string $brief
 * @property \DateTime $time
 * @property int $duration
 * @property int $isForInstructor
 * @property int $status
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package courses
 * @subpackage entity
 */
class Course
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
     * @ORM\Column(type="string")
     * @var string
     */
    public $name;

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
     * @Gedmo\Versioned
     * @ORM\Column(type="text")
     * @var string
     */
    public $brief;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="time")
     * @var \DateTime
     */
    public $time;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer")
     * @var int
     */
    public $duration;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer")
     * @var int
     */
    public $isForInstructor;

    /**
     * @Gedmo\Versioned
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
     * @ORM\ManyToMany(targetEntity="Users\Entity\User", inversedBy="courses")
     * @ORM\JoinTable(name="courses_users",
     *      joinColumns={@ORM\JoinColumn(name="course_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     */
    public $users;

    /**
     * @ORM\OneToOne(targetEntity="Evaluation", mappedBy="course")
     */
    public $evaluation;

    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\Resource", mappedBy="course")
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    public $resources;

    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\Outline", mappedBy="course",cascade={"persist"})
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    public $outlines;

    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\ExamBook", mappedBy="course")
     */
    public $exambook;

    /**
     * Prepare entity
     * 
     * 
     * @access public
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->resources = new ArrayCollection();
        $this->outlines = new ArrayCollection();
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
     * Get Name
     * 
     * 
     * @access public
     * @return string name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     * 
     * 
     * @access public
     * @param string $name
     * @return Course
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return Course
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
     * @return Course
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
     * @return Course
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
     * @return Course
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
     * @return Course
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
     * @return Course
     */
    public function setAi($ai)
    {
        $this->ai = $ai;
        return $this;
    }

    /**
     * Get Brief
     * 
     * 
     * @access public
     * @return string brief
     */
    public function getBrief()
    {
        return $this->brief;
    }

    /**
     * Set brief
     * 
     * 
     * @access public
     * @param string $brief
     * @return Course
     */
    public function setBrief($brief)
    {
        $this->brief = $brief;
        return $this;
    }

    /**
     * Get Time
     * 
     * 
     * @access public
     * @return \DateTime time
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set Time
     * 
     * 
     * @access public
     * @param \DateTime $time
     * @return Course
     */
    public function setTime($time)
    {
        if (!is_object($time)) {
            $time = new \DateTime($time);
        }
        $this->time = $time;
        return $this;
    }

    /**
     * Get duration
     * 
     * 
     * @access public
     * @return int duration
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set duration
     * 
     * 
     * @access public
     * @param int $duration
     * @return Course
     */
    public function setDuration($duration)
    {
        $this->duration = (int) $duration;
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
     * @return Course
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    
    /**
     * Get isForInstructor
     * 
     * 
     * @access public
     * @return int isForInstructor
     */
    public function isForInstructor()
    {
        return $this->isForInstructor;
    }

    /**
     * Set isForInstructor
     * 
     * 
     * @access public
     * @param int $isForInstructor
     * @return Course
     */
    public function setIsForInstructor($isForInstructor)
    {
        $this->isForInstructor = $isForInstructor;
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
     * @return Course
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
     * @return Course
     */
    public function setModified()
    {
        $this->modified = new \DateTime();
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
     * @return Course
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
     * @return Course
     */
    public function setUsers($users)
    {
        $this->users = $users;
        return $this;
    }

    /**
     * Get Resources
     * 
     * 
     * @access public
     * @return ArrayCollection resources
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Add Resources
     * 
     * 
     * @access public
     * @param Courses\Entity\Resource $resource
     * @return Course
     */
    public function addResource($resource)
    {
        $this->resources[] = $resource;
        return $this;
    }

    /**
     * Set Resources
     * 
     * 
     * @access public
     * @param ArrayCollection $resources
     * @return Course
     */
    public function setResources($resources)
    {
        $this->resources = $resources;
        return $this;
    }

    /**
     * Get Outlines
     * 
     * 
     * @access public
     * @return ArrayCollection outlines
     */
    public function getOutlines()
    {
        return $this->outlines;
    }

    /**
     * Set Outlines
     * 
     * 
     * @access public
     * @param ArrayCollection $outlines
     * @return Course
     */
    public function setOutlines($outlines)
    {
        $this->outlines = $outlines;
        return $this;
    }

    /**
     * Remove Outline
     * 
     * @access public
     * @param Courses\Entity\Outline $outline
     * @return Course
     */
    public function removeOutline($outline)
    {
        $outline->setCourse(null);
        $this->outlines->removeElement($outline);
        return $this;
    }

    /**
     * Add Outlines
     * 
     * 
     * @access public
     * @param Courses\Entity\Outline $outline
     * @return Course
     */
    public function addOutline($outline)
    {
        $this->outlines[] = $outline;
        return $this;
    }

    /**
     * Remove Outlines
     * 
     * @access public
     * @param ArrayCollection $outlines
     * @return Course
     */
    public function removeOutlines($outlines)
    {
        foreach ($outlines as $outline) {
            $outline->setCourse(null);
            $this->outlines->removeElement($outline);
        }
        return $this;
    }

    /**
     * Add Outlines
     * 
     * 
     * @access public
     * @param ArrayCollection $outlines
     * @return Course
     */
    public function addOutlines($outlines)
    {
        foreach ($outlines as $outline) {
            $outline->setCourse($this);
            if(is_null($outline->getStatus())){
                $outline->setStatus();
            }
            $this->outlines->add($outline);
        }
        return $this;
    }

    /**
     * Add Evaluation to course
     * 
     * 
     * @access public
     * @param Courses\Entity\Evaluation $evaluation
     * @return Course
     */
    public function setEvaluation($evaluation)
    {
        $this->evaluation = $evaluation;
        return $this;
    }

    /**
     * get Course Evaluation     * 
     * 
     * @access public
     * @param Courses\Entity\Evaluation $evaluation
     * @return Course
     */
    public function getEvaluation()
    {
        return $this->evaluation;
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
        if (array_key_exists('name', $data)) {
            $this->setName($data["name"]);
        }
        if (array_key_exists('status', $data)) {
            $this->setStatus($data["status"]);
        }
        if (array_key_exists('isForInstructor', $data)) {
            $this->setIsForInstructor($data["isForInstructor"]);
        }
        $this->setAi($data["ai"])
                ->setAtp($data["atp"])
                ->setBrief($data["brief"])
                ->setCapacity($data["capacity"])
                ->setDuration($data["duration"])
                ->setEndDate($data["endDate"])
                ->setStartDate($data["startDate"])
                ->setStudentsNo($data["studentsNo"])
                ->setTime($data["time"])
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
                'name' => 'name',
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
            $inputFilter->add(array(
                'name' => 'brief',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'time',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'duration',
                'required' => true,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
