<?php

namespace Courses\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Gedmo\Mapping\Annotation as Gedmo;
use Utilities\Service\Random;
use Utilities\Service\File;

/**
 * PublicQuote Entity
 * @ORM\Entity
 * @ORM\Table(name="public_quote")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property Courses\Entity\CourseEvent $courseEvent
 * @property Users\Entity\User $user
 * @property int $seatsNo
 * @property string $unitPrice
 * @property string $discount
 * @property array $wireTransfer
 * @property int $status
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package courses
 * @subpackage entity
 */
class PublicQuote
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
     * @ORM\ManyToOne(targetEntity="Courses\Entity\CourseEvent", inversedBy="publicQuotes")
     * @ORM\JoinColumn(name="course_event_id", referencedColumnName="id")
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
     * @ORM\Column(type="integer")
     * @var int
     */
    public $seatsNo;
    
    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="decimal", precision=6, scale=2)
     * @var string
     */
    public $unitPrice;
    
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
     * @return PublicQuote
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
     * @return PublicQuote
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get Seats No
     * 
     * 
     * @access public
     * @return int seatsNo
     */
    public function getSeatsNo()
    {
        return $this->seatsNo;
    }

    /**
     * Set Seats No
     * 
     * 
     * @access public
     * @param int $seatsNo
     * @return PublicQuote
     */
    public function setSeatsNo($seatsNo)
    {
        $this->seatsNo = (int) $seatsNo;
        return $this;
    }
    
    /**
     * Get UnitPrice
     * 
     * 
     * @access public
     * @return string unitPrice
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set UnitPrice
     * 
     * 
     * @access public
     * @param float $unitPrice
     * @return PublicQuote
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
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
     * @return PublicQuote
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
     * @return PublicQuote
     */
    public function setWireTransfer($wireTransfer)
    {
        $this->wireTransfer = $wireTransfer;
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
     * @return PublicQuote
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
     * @return PublicQuote
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
     * @return PublicQuote
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
        if (array_key_exists('discount', $data) && ! empty($data["discount"])) {
            $this->setDiscount($data["discount"]);
        }
        if (array_key_exists('wireTransfer', $data) && !empty($data["wireTransfer"]["name"])) {
            $this->setWireTransfer($data["wireTransfer"]);
        }
        $this->setUser($data["user"])
                ->setUnitPrice($data["unitPrice"])
                ->setSeatsNo($data["seatsNo"])
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

            $inputFilter->add(array(
                'name' => 'unitPrice',
                'required' => true,
            ));
            
            $inputFilter->add(array(
                'name' => 'seatsNo',
                'required' => true,
            ));
            
            $target = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . 'quoteWireTransfer' . DIRECTORY_SEPARATOR;
            File::createDir($target);

            $fileUploadOptions = array(
                "target" => $target . Random::getRandomUniqueName(),
                "overwrite" => true,
                "use_upload_name" => false,
                "use_upload_extension" => true
            );
            $inputFilter->add(array(
                'name' => 'file',
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
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
