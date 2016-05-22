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
 * @ORM\Entity(repositoryClass="Courses\Entity\CourseEventRepository")
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
 * @property int $hideFromCalendar
 * @property Organizations\Entity\Organization $atp
 * @property Users\Entity\User $ai
 * @property int $optionId
 * @property int $optionValueId
 * @property int $status
 * @property Doctrine\Common\Collections\ArrayCollection $courseEventUsers
 * @property Doctrine\Common\Collections\ArrayCollection $publicQuotes
 * @property Doctrine\Common\Collections\ArrayCollection $courseEventSubscriptions
 * @property Courses\Entity\PrivateQuote $privateQuote
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
     * @ORM\Column(type="date", nullable=true)
     * @var \DateTime
     */
    public $startDate;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date", nullable=true)
     * @var \DateTime
     */
    public $startDateHj;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date", nullable=true)
     * @var \DateTime
     */
    public $endDate;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date", nullable=true)
     * @var \DateTime
     */
    public $endDateHj;

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
     * @ORM\Column(type="integer")
     * @var int
     */
    public $hideFromCalendar;

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
     * @ORM\OneToMany(targetEntity="Courses\Entity\CourseEventUser", mappedBy="courseEvent")
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    public $courseEventUsers;

    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\PublicQuote", mappedBy="courseEvent")
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    public $publicQuotes;

    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\CourseEventSubscription", mappedBy="courseEvent")
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    public $courseEventSubscriptions;

    /**
     * @ORM\OneToOne(targetEntity="Courses\Entity\PrivateQuote", mappedBy="courseEvent")
     * @var Courses\Entity\PrivateQuote
     */
    public $privateQuote;
    
    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\Vote", mappedBy="courseEvent")
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    public $votes;

    /**
     * @ORM\Column(type="integer", nullable=true);
     * @var int
     */
    public $optionId;

    /**
     * @ORM\Column(type="integer", nullable=true);
     * @var int
     */
    public $optionValueId;

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
        $this->courseEventUsers = new ArrayCollection();
        $this->publicQuotes = new ArrayCollection();
        $this->courseEventSubscriptions = new ArrayCollection();
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
     * Get Start Date
     * 
     * 
     * @access public
     * @return \DateTime startDate
     */
    public function getStartDateHj()
    {
        return $this->startDateHj;
    }

    /*
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
     * Set Start Date
     * 
     * 
     * @access public
     * @param \DateTime $startDateHj
     * @return CourseEvent
     */
    public function setStartDateHj($startDateHj)
    {
        if (!is_object($startDateHj)) {
            $startDateHj = \DateTime::createFromFormat(Time::DATE_FORMAT, $startDateHj);
        }
        $this->startDateHj = $startDateHj;
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
     * Get End Date
     * 
     * 
     * @access public
     * @return \DateTime endDate
     */
    public function getEndDateHj()
    {
        return $this->endDateHj;
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
     * Set End Date
     * 
     * 
     * @access public
     * @param \DateTime $endDateHj
     * @return CourseEvent
     */
    public function setEndDateHj($endDateHj)
    {
        if (!is_object($endDateHj)) {
            $endDateHj = \DateTime::createFromFormat(Time::DATE_FORMAT, $endDateHj);
        }
        $this->endDateHj = $endDateHj;
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
     * Get hideFromCalendar
     * 
     * 
     * @access public
     * @return int hideFromCalendar
     */
    public function getHideFromCalendar()
    {
        return $this->hideFromCalendar;
    }

    /**
     * Set hideFromCalendar
     * 
     * 
     * @access public
     * @param int $hideFromCalendar
     * @return CourseEvent
     */
    public function setHideFromCalendar($hideFromCalendar)
    {
        $this->hideFromCalendar = (int) $hideFromCalendar;
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
     * Get CourseEventUsers
     * 
     * 
     * @access public
     * @return ArrayCollection CourseEventUsers
     */
    public function getCourseEventUsers()
    {
        return $this->courseEventUsers;
    }

    /**
     * Add CourseEventUsers
     * 
     * 
     * @access public
     * @param Courses\Entity\CourseEventUser $courseEventUser
     * @return CourseEvent
     */
    public function addCourseEventUser($courseEventUser)
    {
        $this->courseEventUsers[] = $courseEventUser;
        return $this;
    }

    /**
     * Set CourseEventUsers
     * 
     * 
     * @access public
     * @param ArrayCollection $courseEventUsers
     * @return CourseEvent
     */
    public function setCourseEventUsers($courseEventUsers)
    {
        $this->courseEventUsers = $courseEventUsers;
        return $this;
    }
    
    
    /**
     * Get PublicQuotes
     * 
     * 
     * @access public
     * @return ArrayCollection publicQuotes
     */
    public function getPublicQuotes()
    {
        return $this->publicQuotes;
    }

    /**
     * Set PublicQuotes
     * 
     * 
     * @access public
     * @param ArrayCollection $publicQuotes
     * @return CourseEvent
     */
    public function setPublicQuotes($publicQuotes)
    {
        $this->publicQuotes = $publicQuotes;
        return $this;
    }
    
    /**
     * Get CourseEventSubscriptions
     * 
     * 
     * @access public
     * @return ArrayCollection courseEventSubscriptions
     */
    public function getCourseEventSubscriptions()
    {
        return $this->courseEventSubscriptions;
    }

    /**
     * Set CourseEventSubscriptions
     * 
     * 
     * @access public
     * @param ArrayCollection $courseEventSubscriptions
     * @return CourseEvent
     */
    public function setCourseEventSubscriptions($courseEventSubscriptions)
    {
        $this->courseEventSubscriptions = $courseEventSubscriptions;
        return $this;
    }
    
    /**
     * Get PrivateQuote
     * 
     * 
     * @access public
     * @return Courses\Entity\PrivateQuote privateQuote
     */
    public function getPrivateQuote()
    {
        return $this->privateQuote;
    }

    /**
     * Set PrivateQuote
     * 
     * 
     * @access public
     * @param Courses\Entity\PrivateQuote $privateQuote
     * @return CourseEvent
     */
    public function setPrivateQuote($privateQuote)
    {
        $this->privateQuote = $privateQuote;
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
     * Get OptionId
     * 
     * 
     * @access public
     * @return int optionId
     */
    public function getOptionId()
    {
        return $this->optionId;
    }

    /**
     * Set OptionId
     * 
     * 
     * @access public
     * @param int $optionId
     * @return CourseEvent
     */
    public function setOptionId($optionId)
    {
        $this->optionId = $optionId;
        return $this;
    }

    /**
     * Get OptionValueId
     * 
     * 
     * @access public
     * @return int optionValueId
     */
    public function getOptionValueId()
    {
        return $this->optionValueId;
    }

    /**
     * Set OptionValueId
     * 
     * 
     * @access public
     * @param int $optionValueId
     * @return CourseEvent
     */
    public function setOptionValueId($optionValueId)
    {
        $this->optionValueId = $optionValueId;
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
        if (array_key_exists('optionId', $data)) {
            $this->setOptionId($data["optionId"]);
        }
        if (array_key_exists('optionValueId', $data)) {
            $this->setOptionValueId($data["optionValueId"]);
        }
        if (array_key_exists('ai', $data)) {
            $this->setAi($data["ai"]);
        }
        if (array_key_exists('atp', $data)) {
            $this->setAtp($data["atp"]);
        }
        if (array_key_exists('startDate', $data)) {
            $this->setStartDate($data["startDate"]);
        }
        if (array_key_exists('endDate', $data)) {
            $this->setEndDate($data["endDate"]);
        }
        if (array_key_exists('startDateHj', $data)) {
            $this->setStartDateHj($data["startDateHj"]);
        }
        if (array_key_exists('endDateHj', $data)) {
            $this->setEndDateHj($data["endDateHj"]);
        }
        if (array_key_exists('capacity', $data)) {
            $this->setCapacity($data["capacity"]);
        }
        if (array_key_exists('studentsNo', $data)) {
            $this->setStudentsNo($data["studentsNo"]);
        }
        if (array_key_exists('hideFromCalendar', $data)) {
            $this->setHideFromCalendar($data["hideFromCalendar"]);
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
                'name' => 'hideFromCalendar',
                'required' => false
            ));
            
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
                'name' => 'startDateHj',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'endDateHj',
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
