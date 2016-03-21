<?php

namespace Courses\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Utilities\Service\Status;

/**
 * Outline Entity
 * @ORM\Entity
 * @ORM\Table(name="outline")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 *  
 * @property int $id
 * @property string $title
 * @property string $titleAr
 * @property int $duration
 * @property int $status
 * @property Courses\Entity\Course $course
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package courses
 * @subpackage entity
 */
class Outline
{

    /**
     *
     * @var Array translated properties
     */
    protected $translatedProperties = [
        'title' => [
            'en_US' => 'title',
            'ar_AR' => 'titleAr'
        ],
    ];

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
    public $title;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string")
     * @var string
     */
    public $titleAr;

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
    public $status;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Courses\Entity\Course", inversedBy="outlines")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * @var Courses\Entity\Course
     */
    public $course;

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
     * Get Title
     * 
     * 
     * @access public
     * @return string title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     * 
     * 
     * @access public
     * @param string $title
     * @return Outline
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get Title in Arabic
     * 
     * 
     * @access public
     * @return string title
     */
    public function getTitleAr()
    {
        return $this->titleAr;
    }

    /**
     * Set title in Arabic
     * 
     * 
     * @access public
     * @param string $titleAr
     * @return Outline
     */
    public function setTitleAr($titleAr)
    {
        $this->titleAr = $titleAr;
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
     * @return Outline
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
     * @return Outline
     */
    public function setStatus($status = null)
    {
        if (is_null($status)) {
            $status = Status::STATUS_NOT_APPROVED;
        }
        $this->status = $status;
        return $this;
    }

    /**
     * Get Course
     * 
     * 
     * @access public
     * @return Courses\Entity\Course Course
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
     * @param Courses\Entity\Course $course
     * @return Outline
     */
    public function setCourse($course)
    {
        $this->course = $course;
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
     * @return Outline
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
     * @return Outline
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
        if (isset($data["course"])) {
            $this->setCourse($data["course"]);
        }
        if (isset($data["status"])) {
            $this->setStatus($data["status"]);
        }
        $this->setTitle($data["title"])
                ->setTitleAr($data["titleAr"])
                ->setDuration($data["duration"])
        ;
    }

}
