<?php

namespace IssueTracker\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use IssueTracker\Service\DepthLevel;
use Utilities\Service\Validator\UniqueObject;

/**
 * issue category Entity
 * @ORM\Entity(repositoryClass="IssueTracker\Entity\IssueCategoryRepository")
 * @ORM\Table(name="issue_category",uniqueConstraints={@ORM\UniqueConstraint(name="title_parent_idx", columns={"title","parent_id"})})
 * 
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property string $title
 * @property string $email
 * @property string $description
 * @property int $parent
 * 
 * 
 * @package issuetarcker
 * @subpackage entity
 */
class IssueCategory
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
     * @ORM\Column(type="string");
     * @var string
     */
    public $title;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    public $email;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    public $description;

    /**
     *
     * @ORM\ManyToOne(targetEntity="IssueTracker\Entity\IssueCategory")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id" , nullable=true)
     * @var IssueTracker\Entity\IssueCategory
     */
    public $parent;

    /**
     *
     * @ORM\Column(type="integer")
     * @var int
     */
    public $weight;

    /**
     *
     * @ORM\Column(type="integer")
     * @var int
     */
    public $depth;

    function getId()
    {
        return $this->id;
    }

    function getTitle()
    {
        return $this->title;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getDescription()
    {
        return $this->description;
    }

    function setTitle($title)
    {
        $this->title = $title;
    }

    function setEmail($email)
    {
        $this->email = $email;
    }

    function setDescription($description)
    {
        $this->description = $description;
    }

    function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        if (empty($parent)) {
            $parent = null;
        }
        $this->parent = $parent;
        return $this;
    }

    function getWeight()
    {
        return $this->weight;
    }

    function setWeight($weight)
    {
        $this->weight = $weight;
    }

    function getDepth()
    {
        return $this->depth;
    }

    function setDepth($depth)
    {
        $this->depth = $depth;
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
        $this->setTitle($data['title']);
        $this->setEmail($data['email']);
        $this->setDescription($data['description']);
        $this->setWeight($data['weight']);
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

            $query->setEntity("IssueTracker\Entity\IssueCategory");

            $inputFilter->add(array(
                'name'       => 'title',
                'validators' => array(
                    array(
                        'name'    => 'Utilities\Service\Validator\UniqueObject',
                        'options' => array(
                            'use_context'       => true,
                            'object_manager'    => $query->entityManager,
                            'object_repository' => $query->entityRepository,
                            'fields'            => array('title', 'parent'),
                            'messages'          => array(
                                UniqueObject::ERROR_OBJECT_NOT_UNIQUE => "There is aleady another sub-category with the same name in this category !"
                            )
                        )
                    ),
                ),
                'filters'    => array(
                    array(
                        'name' => 'StringTrim',
                    )
                )
            ));

            $inputFilter->add(array(
                'name'     => 'description',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name'       => 'email',
                'required'   => true,
                'validators' => array(
                    array('name' => 'EmailAddress',
                    ),
                )
            ));

            $inputFilter->add(array(
                'name'     => 'weight',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name'     => 'parent',
                'required' => false,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
