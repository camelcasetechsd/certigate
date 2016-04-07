<?php

namespace Chat\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Users\Entity\User;

/**
 * Message Entity
 * @ORM\Entity
 * @ORM\Table(name="message")
 * 
 * @property int $id
 * @property string $text
 * @property User $sender
 * @property User $recipient
 * @property \DateTime $created
 * 
 * @package chat
 * @subpackage entity
 */
class Message
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
    public $text;

    /**
     * @ORM\ManyToOne(targetEntity="Users\Entity\User", inversedBy="messageFrom")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    public $sender;

    /**
     * @ORM\ManyToOne(targetEntity="Users\Entity\User", inversedBy="messageTo")
     * @ORM\JoinColumn(name="recipient_id", referencedColumnName="id")
     */
    public $recipient;

    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $created;

    function getId()
    {
        return $this->id;
    }

    function getText()
    {
        return $this->text;
    }

    function getSender()
    {
        return $this->sender;
    }

    function getRecipient()
    {
        return $this->recipient;
    }

    function getCreated()
    {
        return $this->created;
    }

    function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }

    function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    function setCreated()
    {
        $this->created = new \DateTime();
        return $this;
    }

    /**
     * Convert the object recipient an array.
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
     * Populate  an array.
     * 
     * 
     * @access public
     * @param array $data ,default is empty array
     */
    public function exchangeArray($data = array())
    {
        if (isset($data['text'])) {
            $this->setText($data['text']);
        }

        if (isset($data['sender'])) {
            $this->setRecipient($data['sender']);
        }

        if (isset($data['messagerecipient'])) {
            $this->setRecipient($data['messagerecipient']);
        }
    }

}