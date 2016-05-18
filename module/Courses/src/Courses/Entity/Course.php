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
 * @property string $nameAr
 * @property Doctrine\Common\Collections\ArrayCollection $resources
 * @property Doctrine\Common\Collections\ArrayCollection $outlines
 * @property Doctrine\Common\Collections\ArrayCollection $courseEvents
 * @property Doctrine\Common\Collections\ArrayCollection $exambooks
 * @property Doctrine\Common\Collections\ArrayCollection $privateQuotes
 * @property string $price
 * @property int $productId
 * @property string $brief
 * @property string $briefAr
 * @property \DateTime $time
 * @property int $duration
 * @property int $isForInstructor
 * @property int $status
 * @property Evaluation $evaluation
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
     * @var Array translated properties
     */
    protected $translatedProperties = [
        'name' => [
            'en_US' => 'name',
            'ar_AR' => 'nameAr'
        ],
        'brief' => [
            'en_US' => 'brief',
            'ar_AR' => 'briefAr'
        ],
    ];

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
     * @ORM\Column(type="string")
     * @var string
     */
    public $nameAr;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="decimal", precision=6, scale=2)
     * @var string
     */
    public $price;

    /**
     * @ORM\Column(type="integer", nullable=false);
     * @var int
     */
    public $productId;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="text")
     * @var string
     */
    public $brief;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="text")
     * @var string
     */
    public $briefAr;

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
     * @Gedmo\Versioned
     * @ORM\OneToOne(targetEntity="Evaluation", mappedBy="course")
     * @var Evaluation
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
     * @ORM\OneToMany(targetEntity="Courses\Entity\CourseEvent", mappedBy="course")
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    public $courseEvents;

    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\ExamBook", mappedBy="course")
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    public $exambooks;

    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\PrivateQuote", mappedBy="course")
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    public $privateQuotes;

    /**
     * Prepare entity
     * 
     * 
     * @access public
     */
    public function __construct()
    {
        $this->resources = new ArrayCollection();
        $this->outlines = new ArrayCollection();
        $this->courseEvents = new ArrayCollection();
        $this->exambooks = new ArrayCollection();
        $this->privateQuotes = new ArrayCollection();
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
     * Get Price
     * 
     * 
     * @access public
     * @return string price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set Price
     * 
     * 
     * @access public
     * @param float $price
     * @return Course
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get ProductId
     * 
     * 
     * @access public
     * @return int productId
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set ProductId
     * 
     * 
     * @access public
     * @param int $productId
     * @return Course
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
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
        // this clause for the inablity of behat to fill input type time 
        if ($this->time != null && APPLICATION_ENV === "test") {
            return $this->time->format('H:i');
        }
        else {
            return $this->time;
        }
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
        $time = Time::objectifyTime($time);
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
     * Get name in Arabic
     * 
     * 
     * @access public
     * @return \DateTime created
     */
    function getNameAr()
    {
        return $this->nameAr;
    }

    /**
     * Set name in Arabic
     * 
     * 
     * @access public
     * @param int $nameAr
     * @return Course
     */
    function setNameAr($nameAr)
    {
        $this->nameAr = $nameAr;
    }

    /**
     * Get brief in Arabic
     * 
     * 
     * @access public
     * @return \DateTime created
     */
    function getBriefAr()
    {
        return $this->briefAr;
    }

    /**
     * Set brief in Arabic
     * 
     * 
     * @access public
     * @param int $briefAr
     * @return Course
     */
    function setBriefAr($briefAr)
    {
        $this->briefAr = $briefAr;
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
     * Get CourseEvents
     * 
     * 
     * @access public
     * @return ArrayCollection courseEvents
     */
    public function getCourseEvents()
    {
        return $this->courseEvents;
    }

    /**
     * Set CourseEvents
     * 
     * 
     * @access public
     * @param ArrayCollection $courseEvents
     * @return Course
     */
    public function setCourseEvents($courseEvents)
    {
        $this->courseEvents = $courseEvents;
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
            if (is_null($outline->getStatus())) {
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
     * Get ExamBooks
     * 
     * 
     * @access public
     * @return ArrayCollection examBooks
     */
    public function getExamBooks()
    {
        return $this->examBooks;
    }

    /**
     * Set ExamBooks
     * 
     * 
     * @access public
     * @param ArrayCollection $examBooks
     * @return Course
     */
    public function setExamBooks($examBooks)
    {
        $this->examBooks = $examBooks;
        return $this;
    }

    /**
     * Get PrivateQuotes
     * 
     * 
     * @access public
     * @return ArrayCollection privateQuotes
     */
    public function getPrivateQuotes()
    {
        return $this->privateQuotes;
    }

    /**
     * Set PrivateQuotes
     * 
     * 
     * @access public
     * @param ArrayCollection $privateQuotes
     * @return Course
     */
    public function setPrivateQuotes($privateQuotes)
    {
        $this->privateQuotes = $privateQuotes;
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
        if (array_key_exists('name', $data)) {
            $this->setName($data["name"]);
        }
        if (array_key_exists('nameAr', $data)) {
            $this->setNameAr($data["nameAr"]);
        }
        if (array_key_exists('status', $data)) {
            $this->setStatus($data["status"]);
        }
        if (array_key_exists('isForInstructor', $data)) {
            $this->setIsForInstructor($data["isForInstructor"]);
        }
        if (array_key_exists('productId', $data)) {
            $this->setProductId($data["productId"]);
        }
        if (array_key_exists('brief', $data)) {
            $this->setBrief($data["brief"]);
        }
        if (array_key_exists('briefAr', $data)) {
            $this->setBriefAr($data["briefAr"]);
        }
        $this->setDuration($data["duration"])
                ->setTime($data["time"])
                ->setPrice($data["price"]);
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
                'name' => 'nameAr',
                'required' => true
            ));

            $inputFilter->add(array(
                'name' => 'brief',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'briefAr',
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
            $inputFilter->add(array(
                'name' => 'price',
                'required' => true,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
