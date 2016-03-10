<?php

namespace Organizations\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Users\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineModule\Validator\UniqueObject;

/**
 * OrganziationMeta Entity
 * @ORM\Entity
 * @ORM\Table(name="organization_meta")
 * @ORM\HasLifecycleCallbacks
 * 
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property Organizations\Entity\Organization $organization 
 * @property Organizations\Entity\OrganizationType $type 
 * @property \DateTime $expirationDate 
 * @property int $expirationFlag 
 * 
 * 
 * @package organizations
 * @subpackage entity
 */
class OrganizationMeta
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
     * @ORM\ManyToOne(targetEntity="Organizations\Entity\OrganizationType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
     * @var Organizations\Entity\OrganizationType
     */
    public $type;

    /**
     * @ORM\ManyToOne(targetEntity="Organizations\Entity\Organization") 
     * @ORM\JoinColumn(name="org_id", referencedColumnName="id", nullable=false) 
     * @var Organizations\Entity\Organization
     */
    public $organization;

    /**
     * @ORM\Column(type="date" , nullable=true)
     * @var \DateTime
     */
    public $expirationDate;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    public $expirationFlag;

    function getId()
    {
        return $this->id;
    }

    function getType()
    {
        return $this->type;
    }

    function getOrganization()
    {
        return $this->organization;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setType($type)
    {
        $this->type = $type;
    }

    function setOrganization($organization)
    {
        $this->organization = $organization;
    }

    function getExpirationDate()
    {
        return $this->expirationDate;
    }

    function getExpirationFlag()
    {
        return $this->expirationFlag;
    }

    function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }

    function setExpirationFlag($expirationFlag)
    {
        $this->expirationFlag = $expirationFlag;
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
     * @param Utilities\Service\Query\Query $query
     * @return InputFilter validation constraints
     */
    public function getInputFilter($query)
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $query->setEntity("Organizations\Entity\OrganizationMeta");


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
