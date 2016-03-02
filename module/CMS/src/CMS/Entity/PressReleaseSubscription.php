<?php

namespace CMS\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Utilities\Service\Random;

/**
 * PressReleaseSubscription Entity
 * @ORM\Entity
 * @ORM\Table(name="press_release_subscription",uniqueConstraints={@ORM\UniqueConstraint(name="user_idx", columns={"user_id"})})
 * @ORM\HasLifecycleCallbacks
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property Users\Entity\User $user
 * @property string $token
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package cms
 * @subpackage entity
 */
class PressReleaseSubscription {

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
     * @ORM\OneToOne(targetEntity="Users\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var Users\Entity\User
     */
    public $user;
    
    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $token;
    
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
     * Get user
     * 
     * 
     * @access public
     * @return string user
     */
    public function getUser() {
        return $this->user;
    }    

    /**
     * Set user
     * 
     * 
     * @access public
     * @param string $user
     * @return PressReleaseSubscription current entity
     */
    public function setUser($user) {
        $this->user = $user;
        return $this;
    }
    
    /**
     * Get token
     * 
     * 
     * @access public
     * @return string token
     */
    public function getToken() {
        return $this->token;
    }
    
    /**
     * Set token
     * 
     * @ORM\PrePersist
     * @access public
     * @return PressReleaseSubscription current entity
     */
    public function setToken() {
        $random = new Random();
        $this->token = $random->getRandomUniqueName();
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
     * @return PressReleaseSubscription current entity
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
     * @return PressReleaseSubscription current entity
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
        if(array_key_exists('user', $data)){
            $this->setUser($data["user"]);
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
                'name' => 'user',
                'required' => true,
                'validators' => array(
                    array('name' => 'DoctrineModule\Validator\UniqueObject',
                        'options' => array(
                            'use_context'   => true,
                            'object_manager' => $query->entityManager,
                            'object_repository' => $query->entityRepository,
                            'fields' => array('user')
                        )
                    ),
                )
            ));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
