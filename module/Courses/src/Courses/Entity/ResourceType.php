<?php

namespace Courses\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

/**
 * ResourceType Entity
 * @ORM\Entity
 * @ORM\Table(name="resource_type")

 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property int $title
 * 
 * 
 * @package courses
 * @subpackage entity
 */
class ResourceType
{

    /**
     * text for presentation type
     */
    const PRESENTATIONS_TYPE_TEXT = 'Presentation';

    /**
     * text for activities type
     */
    const ACTIVITIES_TYPE_TEXT = 'Activities';

    /**
     * text for exams type
     */
    const EXAMS_TYPE_TEXT = 'Exams';

    /**
     * text for course updates type
     */
    const COURSE_UPDATES_TYPE_TEXT = 'Course Updates';

    /**
     * text for standards type
     */
    const STANDARDS_TYPE_TEXT = 'Standards';

    /**
     * text for ice breakers type
     */
    const ICE_BREAKERS_TYPE_TEXT = 'Ice Breakers';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    public $title;

    public function __construct()
    {
        
    }

    function getId()
    {
        return $this->id;
    }

    function getTitle()
    {
        return $this->title;
    }

    function setTitle($title)
    {
        $this->title = $title;
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
        if (array_key_exists("title", $data)) {
            $this->setTitle($data['title']);
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
                'name' => 'questionTitle',
                'required' => true
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
