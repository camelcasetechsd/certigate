<?php

namespace Courses\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Utilities\Service\Random;

/**
 * Resource Entity
 * @ORM\Entity
 * @ORM\Table(name="exambook")
 * @ORM\HasLifecycleCallbacks
 *  
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property \DateTime $date
 * @property int $studentsNo
 * @property int $adminStatus
 * @property int $tvtcStatus
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
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Courses\Entity\Course", inversedBy="exambook")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    public $course;

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

    function getId()
    {
        return $this->id;
    }

    function getCourse()
    {
        return $this->course;
    }

    function getAdminStatus()
    {
        return $this->adminStatus;
    }

    function getTvtcStatus()
    {
        return $this->tvtcStatus;
    }

    function getDate()
    {
        return $this->date;
    }

    function getCreatedAt()
    {
        return $this->createdAt;
    }

    function getAtc()
    {
        return $this->atc;
    }

    function setDate($date)
    {
        $this->date = $date;
    }

    function setCreatedAt($date)
    {
        $this->createdAt = $date;
    }

    function setCourse($course)
    {
        $this->course = $course;
    }

    function setStudentNum($studentsNo)
    {
        $this->studentsNo = $studentsNo;
    }

    function setAtc($atc)
    {
        $this->atc = $atc;
    }

    function setAdminStatus($adminStatus)
    {
        $this->adminStatus = $adminStatus;
    }

    function setTvtcStatus($tvtcStatus)
    {
        $this->tvtcStatus = $tvtcStatus;
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
     * @param int $courseId
     * @param string $name
     * @param bool $overrideFilterFlag ,default is bool false
     * @param array $fileUploadOptions ,default is empty array
     * 
     * @return InputFilter validation constraints
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter || $overrideFilterFlag === true) {
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
                'name' => 'courseId',
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
