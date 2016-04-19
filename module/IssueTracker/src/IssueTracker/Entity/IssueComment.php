<?php

namespace IssueTracker\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

/**
 * issue Entity
 * @ORM\Entity
 * @ORM\Table(name="issue_comment")
 * 
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property string $comment
 * @property Users\Entity\User $creator
 * @property \DateTime $created
 * @property int $status
 * 
 * 
 * @package issuetarcker
 * @subpackage entity
 */
class IssueComment
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
    public $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Users\Entity\User", inversedBy="issueComments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    public $user;

    /**
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $created;

    /**
     * @ORM\ManyToOne(targetEntity="IssueTracker\Entity\Issue", inversedBy="comments")
     * @ORM\JoinColumn(name="issue_id", referencedColumnName="id")
     */
    public $issue;

    function getId()
    {
        return $this->id;
    }

    function getComment()
    {
        return $this->comment;
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

    function setComment($comment)
    {
        $this->comment = $comment;
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

    function getIssue()
    {
        return $this->issue;
    }

    function setIssue($issue)
    {
        $this->issue = $issue;
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
        $this->setComment($data['comment']);
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
                'name' => 'comment',
                'required' => true,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
