<?php

namespace Organizations\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Users\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use Utilities\Service\Time;

/** OrganizationRegion Entity
 * @ORM\Entity
 * @ORM\Table(name="organization_region")
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int    $id
 * @property string $title
 * 
 * 
 * @package organizations
 * @subpackage entity
 */
class OrganizationRegion
{


    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    public $title;
    
    /**
     * @ORM\ManyToMany(targetEntity="Organization", mappedBy="regions")
     */
    public $organizations;
    

    public function __construct() {
        $this->organizations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    function getId()
    {
        return $this->id;
    }

    function getTitle()
    {
        return $this->title;
    }

    function setId($id)
    {
        $this->id = $id;
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
            $query->setEntity("Organizations\Entity\OrganizationType");

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
