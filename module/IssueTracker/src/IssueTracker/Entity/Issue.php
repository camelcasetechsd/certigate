<?php

namespace IssueTracker\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use IssueTracker\Service\DepthLevel;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * issue Entity
 * @ORM\Entity
 * @ORM\Table(name="issue")
 * 
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $category
 * @property string $filePath
 * @property Users\Entity\User $creator
 * @property \DateTime $created
 * 
 * 
 * @package issuetarcker
 * @subpackage entity
 */
class Issue
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
    public $description;

    /**
     *
     * @ORM\ManyToOne(targetEntity="IssueTracker\Entity\IssueCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id" , nullable=true)
     * @var IssueTracker\Entity\IssueCategory
     */
    public $category;

    /**
     * @ORM\Column(type="string" , nullable=true);
     * @var string
     */
    public $filePath;

    /**
     * @ORM\Column(type="integer");
     * @var int
     */
    public $status;

    /**
     * @ORM\ManyToOne(targetEntity="Users\Entity\User", inversedBy="issues")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    public $user;

    /**
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $created;

    /**
     * @ORM\OneToMany(targetEntity="IssueTracker\Entity\IssueComment", mappedBy="issue")
     */
    public $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    function getId()
    {
        return $this->id;
    }

    function getTitle()
    {
        return $this->title;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getCategory()
    {
        return $this->category;
    }

    function getFilePath()
    {
        return $this->filePath;
    }

    function getStatus()
    {
        return $this->status;
    }

    function getUser()
    {
        return $this->user;
    }

    /**
     * Get created
     * 
     * 
     * @access public
     * @return \DateTime created
     */
    public function getCreated()
    {
        return $this->created;
    }

    function getComments()
    {
        return $this->comments;
    }

    function setComments($comments)
    {
        $this->comments = $comments;
    }

    function setTitle($title)
    {
        $this->title = $title;
    }

    function setDescription($description)
    {
        $this->description = $description;
    }

    function setCategory(IssueCategory $category)
    {
        $this->category = $category;
    }

    function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    function setStatus($status)
    {
        $this->status = $status;
    }

    function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Set created
     * 
     * @ORM\PrePersist
     * @access public
     * @return Menu current entity
     */
    public function setCreated()
    {
        $this->created = new \DateTime();
        return $this;
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
        $this->setDescription($data['description']);
        $this->setCreated();
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

            $inputFilter->add(array(
                'name' => 'description',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'title',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'parent',
                'required' => true,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
