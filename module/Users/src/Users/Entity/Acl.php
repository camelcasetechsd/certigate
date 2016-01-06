<?php

namespace Users\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User Entity
 * @ORM\Entity
 * @ORM\Table(name="acl")
 * 
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property \Users\Entity\Role $role
 * @property string $module
 * @property string $route
 * 
 * @package users
 * @subpackage entity
 */
class Acl
{


    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    public $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Users\Entity\Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * @var Users\Entity\Role
     */
    public $role;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $module;

    /**
     *
     * @ORM\Column(type="string")
     * @var string
     */
    public $route;

    /**
     * Gets the value of id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param int $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of role.
     *
     * @return Users\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Sets the value of role.
     *
     * @param Users\Entity\Role $role the role
     *
     * @return self
     */
    public function setRole(\Users\Entity\Role $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Gets the value of module.
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Sets the value of module.
     *
     * @param string $module the module
     *
     * @return self
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Gets the value of route.
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Sets the value of route.
     *
     * @param string $route the route
     *
     * @return self
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }
}
