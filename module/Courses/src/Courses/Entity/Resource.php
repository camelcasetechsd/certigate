<?php

namespace Courses\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Gedmo\Mapping\Annotation as Gedmo;
use Utilities\Service\Random;

/**
 * Resource Entity
 * @ORM\Entity
 * @ORM\Table(name="resource")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 *  
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property string $name
 * @property string $type
 * @property Courses\Entity\Course $course
 * @property array $file
 * @property int $status
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package courses
 * @subpackage entity
 */
class Resource
{

    /**
     * Presentations resource type
     */
    const TYPE_PRESENTATIONS = "Presentations";

    /**
     * Activities resource type
     */
    const TYPE_ACTIVITIES = "Activities";

    /**
     * Exams resource type
     */
    const TYPE_EXAMS = "Exams";

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
    public $type;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Courses\Entity\Course", inversedBy="resources")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * @var Courses\Entity\Course
     */
    public $course;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="array")
     * @var array
     */
    public $file;

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
     * Set Name
     * 
     * 
     * @access public
     * @param string $name
     * @return Resource
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get Type
     * 
     * 
     * @access public
     * @return string type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set Type
     * 
     * 
     * @access public
     * @param string $type
     * @return Resource
     */
    public function setType($type)
    {
        $this->type = $type;
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
     * @return Resource
     */
    public function setCourse($course)
    {
        $this->course = $course;
        return $this;
    }

    /**
     * Get File
     * 
     * 
     * @access public
     * @return array file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set File
     * 
     * 
     * @access public
     * @param array $file
     * @return Resource
     */
    public function setFile($file)
    {
        $this->file = $file;
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
     * @return Resource
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
     * @return Resource
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
     * @return Resource
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
        if (array_key_exists('status', $data)) {
            $this->setStatus($data["status"]);
        }
        if (array_key_exists('file', $data) && !empty($data["file"]["name"])) {
            $this->setFile($data["file"]);
        }
        $this->setName($data["name"])
                ->setType($data["type"])
                ->setCourse($data["course"])
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
     * @param int $courseId
     * @param string $name
     * @param bool $overrideFilterFlag ,default is bool false
     * @param array $fileUploadOptions ,default is empty array
     * 
     * @return InputFilter validation constraints
     */
    public function getInputFilter($courseId, $name, $overrideFilterFlag = false, &$fileUploadOptions = array())
    {
        if (!$this->inputFilter || $overrideFilterFlag === true) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'name',
                'required' => true,
            ));
            
            $inputFilter->add(array(
                'name' => 'type',
                'required' => true,
            ));
            $inputFilter->add(array(
                'name' => 'course',
                'required' => true,
            ));

            $random = new Random();
            $unique = $random->getRandomUniqueName();
            $DirSep = DIRECTORY_SEPARATOR;
            $target = APPLICATION_PATH . $DirSep . 'upload' . $DirSep . 'courseResources' . $DirSep . $courseId . $DirSep;
            $useUploadName = true;
            if (!file_exists($target)) {
                // PHP takes 0777 and substracts the current value of umask
                $oldUmask = umask(0);
                mkdir($target, 0777);
                // return back umask to it's original value
                umask($oldUmask);
            }
            if (is_string($name) && strlen($name) > 0) {
                $target .= $name."_".$unique;
                $useUploadName = false;
            }

            $fileUploadOptions = array(
                "target" => $target,
                "overwrite" => true,
                "use_upload_name" => $useUploadName,
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
                            'extension' => 'zip,pdf,ppt,pot,pps,pptx,potx,ppsx,thmx'
                        )
                    ),
                )
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
