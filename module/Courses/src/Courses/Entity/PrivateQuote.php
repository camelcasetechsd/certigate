<?php

namespace Courses\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Gedmo\Mapping\Annotation as Gedmo;
use Utilities\Service\Random;
use Utilities\Service\File;
use Utilities\Service\Time;
use Utilities\Service\Status;

/**
 * PrivateQuote Entity
 * @ORM\Entity
 * @ORM\Table(name="private_quote")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property Courses\Entity\CourseEvent $courseEvent
 * @property Courses\Entity\Course $course
 * @property Users\Entity\User $user
 * @property Courses\Entity\PrivateQuoteVenue $venue
 * @property string $price
 * @property string $discount
 * @property array $wireTransfer
 * @property int $status
 * @property \DateTime $preferredDate
 * @property \DateTime $preferredDateHj
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package courses
 * @subpackage entity
 */
class PrivateQuote
{

    const QUOTE_TYPE = "Private";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    public $id;

    /**
     * @ORM\ManyToOne(targetEntity="Courses\Entity\Course", inversedBy="privateQuotes")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * @var Courses\Entity\Course
     */
    public $course;

    /**
     * @ORM\OneToOne(targetEntity="Courses\Entity\CourseEvent", inversedBy="privateQuote")
     * @var Courses\Entity\CourseEvent
     */
    public $courseEvent;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Users\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var Users\Entity\User
     */
    public $user;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Courses\Entity\PrivateQuoteVenue")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     * @var Courses\Entity\PrivateQuoteVenue
     */
    public $venue;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=true)
     * @var string
     */
    public $price;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=true)
     * @var string
     */
    public $discount;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="array", nullable=true)
     * @var array
     */
    public $wireTransfer;

    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $preferredDate;
    
    /**
     * 
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $preferredDateHj;

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
     * @ORM\Column(type="integer")
     * @var int
     */
    public $status;

    /**
     *
     * @var InputFilter validation constraints 
     */
    private $inputFilter;

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
     * @return PrivateQuote
     */
    public function setCourseEvent($courseEvent)
    {
        $this->courseEvent = $courseEvent;
        return $this;
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
     * @return PrivateQuote
     */
    public function setCourse($course)
    {
        $this->course = $course;
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
     * @return PrivateQuote
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get Venue
     * 
     * 
     * @access public
     * @return string venue
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * Set Venue
     * 
     * 
     * @access public
     * @param string $venue
     * @return PrivateQuote
     */
    public function setVenue($venue)
    {
        $this->venue = $venue;
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
     * @return PrivateQuote
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get Discount
     * 
     * 
     * @access public
     * @return string discount
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set Discount
     * 
     * 
     * @access public
     * @param float $discount
     * @return PrivateQuote
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * Get WireTransfer
     * 
     * 
     * @access public
     * @return array wireTransfer
     */
    public function getWireTransfer()
    {
        return $this->wireTransfer;
    }

    /**
     * Set WireTransfer
     * 
     * 
     * @access public
     * @param array $wireTransfer
     * @return PrivateQuote
     */
    public function setWireTransfer($wireTransfer)
    {
        $this->wireTransfer = $wireTransfer;
        return $this;
    }

    /**
     * Get PreferredDate
     * 
     * 
     * @access public
     * @return \DateTime preferredDate
     */
    public function getPreferredDate()
    {
        return $this->preferredDate;
    }

    /**
     * Set PreferredDate
     * 
     * @access public
     * @param \DateTime $preferredDate
     * @return PrivateQuote
     */
    public function setPreferredDate($preferredDate)
    {
        if (!is_object($preferredDate)) {
            $preferredDate = \DateTime::createFromFormat(Time::DATE_FORMAT, $preferredDate);
        }
        $this->preferredDate = $preferredDate;
        return $this;
    }

    /**
     * Get PreferredDate
     * 
     * 
     * @access public
     * @return \DateTime preferredDate
     */
    public function getPreferredDateHj()
    {
        return $this->preferredDateHj;
    }
    
    /**
     * Set PreferredDate
     * 
     * 
     * @access public
     * @param \DateTime $preferredDateHj
     * @return PrivateQuote
     */
    public function setPreferredDateHj($preferredDateHj)
    {
        if (!is_object($preferredDateHj)) {
            $preferredDateHj = \DateTime::createFromFormat(Time::DATE_FORMAT, $preferredDateHj);
        }
        $this->preferredDateHj = $preferredDateHj;
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
     * @return PrivateQuote
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
     * @return PrivateQuote
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
     * @return PrivateQuote
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
        if (array_key_exists('courseEvent', $data)) {
            $this->setCourseEvent($data["courseEvent"]);
        }
        if (array_key_exists('price', $data)) {
            $this->setPrice($data["price"]);
        }
        if (array_key_exists('discount', $data) && !empty($data["discount"])) {
            $this->setDiscount($data["discount"]);
        }
        if (array_key_exists('wireTransfer', $data) && !empty($data["wireTransfer"]["name"])) {
            $this->setWireTransfer($data["wireTransfer"]);
        }
        if (array_key_exists('user', $data)) {
            $this->setUser($data["user"]);
        }
        if (array_key_exists('course', $data)) {
            $this->setCourse($data["course"]);
        }
        if (array_key_exists('venue', $data)) {
            $this->setVenue($data["venue"]);
        }
        if (array_key_exists('preferredDate', $data)) {
            $this->setPreferredDate($data["preferredDate"]);
        }
        if (array_key_exists('preferredDateHj', $data)) {
            $this->setPreferredDateHj($data["preferredDateHj"]);
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
     * @param int $status
     * @return InputFilter validation constraints
     */
    public function getInputFilter($status)
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            if ($status == Status::STATUS_PENDING_PRICING) {
                $inputFilter->add(array(
                    'name' => 'courseEvent',
                    'required' => true
                ));

                $inputFilter->add(array(
                    'name' => 'price',
                    'required' => true,
                ));
            }
            if ($status == Status::STATUS_PENDING_PAYMENT || $status == Status::STATUS_PENDING_REPAYMENT) {
                $target = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . 'quoteWireTransfer' . DIRECTORY_SEPARATOR;
                File::createDir($target);

                $fileUploadOptions = array(
                    "target" => $target . Random::getRandomUniqueName(),
                    "overwrite" => true,
                    "use_upload_name" => false,
                    "use_upload_extension" => true
                );
                $inputFilter->add(array(
                    'name' => 'wireTransfer',
                    'required' => true,
                    'filters' => array(
                        array(
                            "name" => "Zend\Filter\File\RenameUpload",
                            "options" => $fileUploadOptions
                        ),
                    ),
                    'validators' => array(
                        array('name' => 'Fileextension',
                            'options' => array(
                                'extension' => 'zip,gif,jpg,png,pdf'
                            )
                        ),
                    )
                ));
            }
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
