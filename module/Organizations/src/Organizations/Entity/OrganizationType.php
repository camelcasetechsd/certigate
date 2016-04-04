<?php

namespace Organizations\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Users\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use Utilities\Service\Time;

/** OrganziationType Entity
 * @ORM\Entity
 * @ORM\Table(name="organization_type")
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int    $id
 * @property string $title
 * 
 * 
 * @package organizations
 * @subpackage entity
 */
class OrganizationType
{

    /**
     * authenticated training center 
     */
    const TYPE_ATC = 1;

    /**
     * authenticated training partner
     */
    const TYPE_ATP = 2;

    /**
     * authenticated distributor
     */
    const TYPE_DISTRIBUTOR = 3;

    /**
     * authenticated re-seller
     */
    const TYPE_RESELLER = 4;
    
    
    /**
     * authenticated training center title
     */
    const TYPE_ATC_TITLE = "ATC";

    /**
     * authenticated training partner title
     */
    const TYPE_ATP_TITLE = "ATP";

    /**
     * distributor title
     */
    const TYPE_DISTRIBUTOR_TITLE = "Distributor";

    /**
     * re-seller title
     */
    const TYPE_RESELLER_TITLE = "Re-Seller";

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
