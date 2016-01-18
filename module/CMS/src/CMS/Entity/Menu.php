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
 * @property int $status
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package cms
 * @subpackage entity
 */
class Menu {

    /**
     * Primary menu
     */
    const PRIMARY_MENU_UNDERSCORED = "primary_menu";
    
    /**
     * Admin menu
     */
    const ADMIN_MENU_UNDERSCORED = "admin_menu";
    
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
    public function getId() {
        return $this->id;
    }  
    
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
     * Get status
     * 
     * 
     * @access public
     * @return int status
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * Set status
     * 
     * 
     * @access public
     * @param int $status
     * @return Menu current entity
     */
    public function setStatus($status) {
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
        if(array_key_exists('status', $data)){
            $this->setStatus($data["status"]);
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
     * @param Utilities\Service\Query\Query $query
     * @return InputFilter validation constraints
     */
    public function getInputFilter($query) {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'title',
                'required' => true,
                'validators' => array(
                    array('name' => 'DoctrineModule\Validator\UniqueObject',
                        'options' => array(
                            'use_context'   => true,
                            'object_manager' => $query->entityManager,
                            'object_repository' => $query->entityRepository,
                            'fields' => array('title')
                        )
                    ),
                )
            ));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
