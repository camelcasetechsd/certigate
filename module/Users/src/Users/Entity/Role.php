<?php

namespace Users\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Role Entity
 * @ORM\Entity
 * @ORM\Table(name="role")
 * 
 * 
 * @property int $id
 * @property string $name
 * 
 * @package users
 * @subpackage entity
 */
class Role
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
     * @ORM\Column(type="string")
     * @var string
     */
    public $name;

}