<?php

namespace Organizations\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Users\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineModule\Validator\UniqueObject;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * OrganziationUser Entity
 * @ORM\Entity
 * @ORM\Table(name="organization_user",uniqueConstraints={@ORM\UniqueConstraint(name="user_role_organization_idx", columns={"role_id","user_id","org_id"})})
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * 
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property int $distanceSort 
 * @property Users\Entity\Role $role 
 * @property Users\Entity\User $user 
 * @property Organizations\Entity\Organization $organization 
 * @property Doctrine\Common\Collections\ArrayCollection $proctorExamBooks
 * 
 * @package organizations
 * @subpackage entity
 */
class OrganizationUser
{

    /**
     *
     * @var InputFilter validation constraints 
     */
    private $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    public $id;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    public $distanceSort;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Users\Entity\Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false)
     * @var Users\Entity\Role
     */
    public $role;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Users\Entity\User", inversedBy="organizationUser") 
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false) 
     * @var Users\Entity\User
     */
    public $user;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Organizations\Entity\Organization", inversedBy="organizationUser") 
     * @ORM\JoinColumn(name="org_id", referencedColumnName="id", nullable=false) 
     * @var Organizations\Entity\Organization
     */
    public $organization;

    /**
     * @ORM\OneToMany(targetEntity="Courses\Entity\ExamBook", mappedBy="proctors")
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    public $proctorExamBooks;
    
    /**
     * Prepare organizationUser entity
     * 
     * 
     * @access public
     */
    public function __construct()
    {
        $this->proctorExamBooks = new ArrayCollection();
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
     * Set Both Organization and User
     * 
     * @access public
     * 
     * @param \Organizations\Entity\Organization $organization
     * @param User $user
     * 
     * @return \Organizations\Entity\OrganizationUser
     */
    public function setOrganizationUser($organization, $user)
    {
        $this->organization = $organization;
        $this->user = $user;
        return $this;
    }

    /**
     * Set DistanceSort
     * 
     * @access public
     * @param int $distanceSort
     * @return \Organizations\Entity\OrganizationUser
     */
    public function setDistanceSort($distanceSort)
    {
        $this->distanceSort = $distanceSort;
        return $this;
    }

    /**
     * Get DistanceSort
     * 
     * @access public
     * @return int distanceSort
     */
    public function getDistanceSort()
    {
        return $this->distanceSort;
    }

    /**
     * Get Distance Corresponding class
     * 
     * @access public
     * @return string bootstrap class
     */
    public function getDistanceStyleClass()
    {
        if($this->distanceSort <= 10){
            $distanceStyleClass = "bg-success";
        }elseif($this->distanceSort <= 100){
            $distanceStyleClass = "bg-info";
        }elseif($this->distanceSort <= 300){
            $distanceStyleClass = "bg-warning";
        }else{
            $distanceStyleClass = "bg-danger";
        }
        return $distanceStyleClass;
    }

    /**
     * Set Role
     * 
     * @access public
     * @param Users\Entity\Role $role
     * @return \Organizations\Entity\OrganizationUser
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Get Role
     * 
     * @access public
     * @return Users\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set Organization
     * 
     * @access public
     * @param Organizations\Entity\Organization $organization
     * @return \Organizations\Entity\OrganizationUser
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * Get Organization
     * 
     * @access public
     * @return Organizations\Entity\Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set User
     * 
     * @access public
     * @param Users\Entity\User $user
     * @return \Organizations\Entity\OrganizationUser
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get User
     * 
     * @access public
     * @return Users\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get ProctorExamBooks
     * 
     * 
     * @access public
     * @return ArrayCollection proctorExamBooks
     */
    public function getProctorExamBooks()
    {
        return $this->proctorExamBooks;
    }

    /**
     * Set ProctorExamBooks
     * 
     * 
     * @access public
     * @param ArrayCollection $proctorExamBooks
     * @return \Organizations\Entity\OrganizationUser
     */
    public function setProctorExamBooks($proctorExamBooks)
    {
        $this->proctorExamBooks = $proctorExamBooks;
        return $this;
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
        if (array_key_exists("organization", $data)) {
            $this->setOrganization($data["organization"]);
        }
        if (array_key_exists("distanceSort", $data)) {
            $this->setDistanceSort($data["distanceSort"]);
        }
        $this->setRole($data["role"])
                ->setUser($data["user"]);
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
            $query->setEntity("Organizations\Entity\OrganizationUser");

            $inputFilter->add(array(
                'name' => 'user',
                'required' => true,
                'validators' => array(
                    array('name' => 'Utilities\Service\Validator\UniqueObject',
                        'options' => array(
                            'use_context' => true,
                            'object_manager' => $query->entityManager,
                            'object_repository' => $query->entityRepository,
                            'fields' => array('user', 'organization', 'role'),
                            'messages' => array(UniqueObject::ERROR_OBJECT_NOT_UNIQUE => "There is already another organization user with the same user and role")
                        )
                    ),
                )
            ));
            $inputFilter->add(array(
                'name' => 'role',
                'required' => true,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
