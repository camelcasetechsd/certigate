<?php

namespace CMS\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

/**
 * MenuItem Entity
 * @ORM\Entity
 * @ORM\Table(name="menuItem",uniqueConstraints={@ORM\UniqueConstraint(name="path_idx", columns={"path"})})
 * @ORM\HasLifecycleCallbacks
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property string $title
 * @property string $path
 * @property CMS\Entity\Page $page
 * @property CMS\Entity\Menu $menu
 * @property CMS\Entity\MenuItem $parent
 * @property int $status
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package cms
 * @subpackage entity
 */
class MenuItem {

    /**
     * MenuItem is active
     */
    const STATUS_ACTIVE = 1;
    /**
     * MenuItem is inactive
     */
    const STATUS_INACTIVE = 0;
    
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
     * @ORM\Column(type="string")
     * @var string
     */
    public $title;
    
    /**
     *
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    public $path;

    /**
     *
     * @ORM\OneToOne(targetEntity="CMS\Entity\Page", mappedBy="menuItem")
     * @var CMS\Entity\Page
     */
    public $page;

    /**
     *
     * @ORM\ManyToOne(targetEntity="CMS\Entity\Menu")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     * @var CMS\Entity\Menu
     */
    public $menu;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="CMS\Entity\MenuItem")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id" , nullable=true)
     * @var CMS\Entity\MenuItem
     */
    public $parent;
    
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
     * @return MenuItem current entity
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    
    /**
     * Get path
     * 
     * 
     * @access public
     * @return string path
     */
    public function getPath() {
        return $this->path;
    }    

    /**
     * Set path
     * 
     * 
     * @access public
     * @param string $path
     * @return MenuItem current entity
     */
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }
    
    /**
     * Get page
     * 
     * 
     * @access public
     * @return CMS\Entity\Page page
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * Set page
     * 
     * 
     * @access public
     * @param CMS\Entity\Page $page
     * @return MenuItem current entity
     */
    public function setPage($page) {
        $this->page = $page;
        return $this;
    }
    
    /**
     * Get menu
     * 
     * 
     * @access public
     * @return CMS\Entity\Menu menu
     */
    public function getMenu() {
        return $this->menu;
    }

    /**
     * Set menu
     * 
     * 
     * @access public
     * @param CMS\Entity\Menu $menu
     * @return MenuItem current entity
     */
    public function setMenu($menu) {
        $this->menu = $menu;
        return $this;
    }
    
    /**
     * Get parent
     * 
     * 
     * @access public
     * @return CMS\Entity\MenuItem parent
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Set parent
     * 
     * 
     * @access public
     * @param CMS\Entity\MenuItem $parent
     * @return MenuItem current entity
     */
    public function setParent($parent) {
        if(empty($parent)){
            $parent = null;
        }
        $this->parent = $parent;
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
     * @return MenuItem current entity
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
     * @return MenuItem current entity
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
     * @return MenuItem current entity
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
        if(array_key_exists('path', $data)){
            $this->setPath($data["path"]);
        }
        if(array_key_exists('page', $data)){
            $this->setPage($data["page"]);
        }
        if(array_key_exists('menu', $data)){
            $this->setMenu($data["menu"]);
        }
        if(array_key_exists('parent', $data)){
            $this->setParent($data["parent"]);
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
     * @return InputFilter validation constraints
     */
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'title',
                'required' => true
            ));
            $inputFilter->add(array(
                'name' => 'path',
                'required' => true
            ));
            $inputFilter->add(array(
                'name' => 'menu',
                'required' => true
            ));
            $inputFilter->add(array(
                'name' => 'parent',
                'required' => false
            ));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
