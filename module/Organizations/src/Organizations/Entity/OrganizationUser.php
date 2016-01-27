<?php

namespace Organizations\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Users\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;

//use Gedmo\Mapping\Annotation as Gedmo;

/**
 * OrganziationUser Entity
 * @ORM\Entity
 * @ORM\Table(name="organization_user")
 * @Gedmo\Loggable
 * 
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int    $id
 * @property int    $type 
 * 
 * 
 * @package orgs
 * @subpackage entity
 */
class OrganizationUser
{

    /**
     * training manger
     */
    const TYPE_TRAINING_MANAGER = 1;

    /**
     * Test Center Admin
     */
    const TYPE_TEST_CENTER_ADMIN = 2;

    /**
     * Proctor
     */
    const TYPE_PROCTOR = 3;

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
     * @ORM\Column(type="integer")
     * @var int
     */
    public $type;

    /**
     * @ORM\ManyToOne(targetEntity="Users\Entity\User", inversedBy="organizationUser") 
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false) 
     */
    public $user;

    /**
     * @ORM\ManyToOne(targetEntity="Organizations\Entity\Organization", inversedBy="organizationUser") 
     * @ORM\JoinColumn(name="org_id", referencedColumnName="id", nullable=false) 
     */
    public $organization;

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

    public function setOrganizationUser(Organization $organization, User $user)
    {
        $this->organization = $organization;
        $this->user = $user;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        $this->type;
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
    public function getInputFilter($query)
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
