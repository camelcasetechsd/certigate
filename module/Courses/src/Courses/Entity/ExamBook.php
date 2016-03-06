<?php

namespace Courses\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Utilities\Service\Time;

/**
 * Resource Entity
 * @ORM\Entity
 * @ORM\Table(name="exambook")
 * @ORM\HasLifecycleCallbacks
 *  
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property \DateTime $date
 * @property Courses\Entity\CourseEvent $courseEvent
 * @property Organizations\Entity\Organization $atc
 * @property int $studentsNo
 * @property int $adminStatus
 * @property int $tvtcStatus
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package courses
 * @subpackage entity
 */
class ExamBook
{

    /**
     * Admin has approved exam request
     */
    const ADMIN_APPROVED = 1;

    /**
     * Admin has declined exam request
     */
    const ADMIN_DECLINED = 2;

    /**
     * Admin has not resonded to exam request
     */
    const ADMIN_PENDING = 3;

    /**
     * TVTC has approved exam request
     */
    const TVTC_APPROVED = 1;

    /**
     * TVTC has Declined exam request
     */
    const TVTC_DECLINED = 2;

    /**
     * TVTC has not responded to exam request
     */
    const TVTC_PENDING = 3;

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
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $date;
    
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
     * @ORM\ManyToOne(targetEntity="Courses\Entity\CourseEvent", inversedBy="exambooks")
     * @ORM\JoinColumn(name="course_event_id", referencedColumnName="id")
     */
    public $courseEvent;

    /**
     * @ORM\ManyToOne(targetEntity="Organizations\Entity\Organization", inversedBy="exambook")
     * @ORM\JoinColumn(name="atc_id", referencedColumnName="id")
     */
    public $atc;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    public $studentsNo;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    public $adminStatus;

    /**
     * @ORM\Column(type="integer" , nullable = true)
     * @var int
     */
    public $tvtcStatus;

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
     * Get courseEvent
     * 
     * @access public
     * @return Courses\Entity\CourseEvent
     */
    public function getCourseEvent()
    {
        return $this->courseEvent;
    }

    /**
     * Get adminStatus
     * 
     * @access public
     * @return int
     */
    public function getAdminStatus()
    {
        return $this->adminStatus;
    }

    /**
     * Get tvtcStatus
     * 
     * @access public
     * @return int
     */
    public function getTvtcStatus()
    {
        return $this->tvtcStatus;
    }

    /**
     * Get date
     * 
     * @access public
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get atc
     * 
     * @access public
     * @return Organizations\Entity\Organization
     */
    public function getAtc()
    {
        return $this->atc;
    }

    /**
     * Set date
     * 
     * @access public
     * @param \DateTime $date
     * @return Courses\Entity\ExamBook
     */
    public function setDate($date)
    {
        if (!is_object($date)) {
            $date = \DateTime::createFromFormat(Time::DATE_FORMAT, $date);
        }
        $this->date = $date;
        return $this;
    }

    /**
     * Set courseEvent
     * 
     * @access public
     * @param Courses\Entity\CourseEvent $courseEvent
     * @return Courses\Entity\ExamBook
     */
    public function setCourseEvent($courseEvent)
    {
        $this->courseEvent = $courseEvent;
        return $this;
    }

    /**
     * Set studentsNo
     * 
     * @access public
     * @param int $studentsNo
     * @return Courses\Entity\ExamBook
     */
    public function setStudentsNo($studentsNo)
    {
        $this->studentsNo = $studentsNo;
        return $this;
    }

    /**
     * Set atc
     * 
     * @access public
     * @param Organizations\Entity\Organization $atc
     * @return Courses\Entity\ExamBook
     */
    public function setAtc($atc)
    {
        $this->atc = $atc;
        return $this;
    }

    /**
     * Set AdminStatus
     * 
     * @access public
     * @param int $adminStatus
     * @return Courses\Entity\ExamBook
     */
    public function setAdminStatus($adminStatus)
    {
        $this->adminStatus = $adminStatus;
        return $this;
    }

    /**
     * Set TvtcStatus
     * 
     * @access public
     * @param int $tvtcStatus
     * @return Courses\Entity\ExamBook
     */
    public function setTvtcStatus($tvtcStatus)
    {
        $this->tvtcStatus = $tvtcStatus;
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
     * @return ExamBook
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
     * @return ExamBook
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
        if(array_key_exists("tvtcStatus", $data)){
            $this->setTvtcStatus($data["tvtcStatus"]);
        }
        $this->setAdminStatus($data["adminStatus"])
                ->setAtc($data["atc"])
                ->setCourseEvent($data["courseEvent"])
                ->setDate($data["date"])
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
     * 
     * @return InputFilter validation constraints
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'date',
                'required' => true,
                'validators' => array(
                    array('name' => 'Utilities\Service\Validator\TenDaysAfterValidator',
                        'options' => array(
                            'token' => 'startDate'
                        )
                    ),
                )
            ));
            $inputFilter->add(array(
                'name' => 'studentsNo',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'courseEvent',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'atcId',
                'required' => true,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
