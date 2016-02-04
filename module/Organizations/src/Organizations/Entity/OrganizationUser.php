<?php

namespace Organizations\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Users\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineModule\Validator\UniqueObject;

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
 * @property Users\Entity\Role $role 
 * @property Users\Entity\User $user 
 * @property Organizations\Entity\Organization $organization 
 * 
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
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    public $id;

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
