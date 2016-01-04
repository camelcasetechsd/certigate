<?php

namespace CMS\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

/**
 * Menu Entity
 * @ORM\Entity
 * @ORM\Table(name="menu",uniqueConstraints={@ORM\UniqueConstraint(name="title_idx", columns={"title"})})
 * @ORM\HasLifecycleCallbacks
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property string $title
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package cms
 * @subpackage entity
 */
class Menu {

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
     *
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    public $title;

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
     * Get title
     * 
     * 
     * @access public
     * @return string title
     */
    public function getTitle() {
        return $this->title;
    }    

    /**
     * Set title
     * 
     * 
     * @access public
     * @param string $title
     * @return Menu current entity
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    
    /**
     * Get created
     * 
     * 
     * @access public
     * @return \DateTime created
     */
    public function getCreated() {
        return $this->created;
    }
    
    /**
     * Set created
     * 
     * @ORM\PrePersist
     * @access public
     * @return Menu current entity
     */
    public function setCreated() {
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
    public function getModified() {
        return $this->modified;
    }
    
    /**
     * Set modified
     * 
     * @ORM\PreUpdate
     * @access public
     * @return Menu current entity
     */
    public function setModified() {
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
    public function getArrayCopy() {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     * 
     * 
     * @access public
     * @param array $data ,default is empty array
     */
    public function exchangeArray($data = array()) {
        if(array_key_exists('title', $data)){
            $this->setTitle($data["title"]);
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
    public function setInputFilter(InputFilterInterface $inputFilter) {
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
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'title',
                'required' => true
            ));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
