<?php

namespace IssueTracker\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use IssueTracker\Service\DepthLevel;

/**
 * issue Entity
 * @ORM\Entity
 * @ORM\Table(name="issue",uniqueConstraints={@ORM\UniqueConstraint(name="title_idx", columns={"title"})})
 * 
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $category
 * @property string $filePath
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
     * @ORM\Column(type="string");
     * @var string
     */
    public $filePath;

    /**
     * @ORM\Column(type="integer");
     * @var int
     */
    public $status;

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
