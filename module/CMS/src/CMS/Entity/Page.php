<?php

namespace CMS\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

/**
 * Page Entity
 * @ORM\Entity
 * @ORM\Table(name="page",uniqueConstraints={@ORM\UniqueConstraint(name="menuitem_idx", columns={"menuitem_id"})})
 * @ORM\HasLifecycleCallbacks
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property string $body
 * @property CMS\Entity\MenuItem $menuItem
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package cms
 * @subpackage entity
 */
class Page {

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
    public $body;

    /**
     *
     * @ORM\OneToOne(targetEntity="CMS\Entity\MenuItem", inversedBy="page")
     * @ORM\JoinColumn(name="menuitem_id", referencedColumnName="id")
     * @var CMS\Entity\MenuItem
     */
    public $menuItem;
    
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
     * Get body
     * 
     * 
     * @access public
     * @return string body
     */
    public function getBody() {
        return gzuncompress(base64_decode($this->body));
    }    

    /**
     * Set body
     * 
     * 
     * @access public
     * @param string $body
     * @return Page current entity
     */
    public function setBody($body) {
        // compress large page content
        // encode data, so that binary data survive transport through transport layers that are not 8-bit clean
        $this->body = base64_encode(gzcompress($body));
        return $this;
    }
    
    /**
     * Get menuItem
     * 
     * 
     * @access public
     * @return CMS\Entity\MenuItem menuItem
     */
    public function getMenuItem() {
        return $this->menuItem;
    }

    /**
     * Set menuItem
     * 
     * 
     * @access public
     * @param CMS\Entity\MenuItem $menuItem
     * @return Page current entity
     */
    public function setMenuItem($menuItem) {
        $this->menuItem = $menuItem;
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
     * @return Page current entity
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
     * @return Page current entity
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
        if(array_key_exists('body', $data)){
            $this->setBody($data["body"]);
        }
        if(array_key_exists('menuItem', $data)){
            $this->setMenuItem($data["menuItem"]);
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
                'name' => 'body',
                'required' => true
            ));
            $inputFilter->add(array(
                'name' => 'menuItem',
                'required' => true,
                'validators' => array(
                    array('name' => 'DoctrineModule\Validator\UniqueObject',
                        'options' => array(
                            'use_context'   => true,
                            'object_manager' => $query->entityManager,
                            'object_repository' => $query->entityRepository,
                            'fields' => array('menuItem')
                        )
                    ),
                )
            ));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
