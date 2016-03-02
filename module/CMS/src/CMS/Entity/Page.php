<?php

namespace CMS\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Utilities\Service\Random;

/**
 * Page Entity
 * @ORM\Entity(repositoryClass="CMS\Entity\PageRepository")
 * @ORM\Table(name="page")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property string $title
 * @property string $path
 * @property string $type
 * @property string $category
 * @property string $author
 * @property array $picture
 * @property string $summary
 * @property string $body
 * @property int $status
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package cms
 * @subpackage entity
 */
class Page
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
     *
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     * @var string
     */
    public $title;

    /**
     *
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     * @var string
     */
    public $path;

    /**
     *
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     * @var string
     */
    public $type;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @var string
     */
    public $category;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @var string
     */
    public $author;

    /**
     *
     * @ORM\Column(type="array", nullable=true)
     * @Gedmo\Versioned
     * @var array
     */
    public $picture;

    /**
     *
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     * @var string
     */
    public $summary;

    /**
     *
     * @ORM\Column(type="text")
     * @Gedmo\Versioned
     * @var string
     */
    public $body;

    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $created;

    /**
     *
     * @ORM\Column(type="integer")
     * @var int
     */
    public $status;

    /**
     *
     * @ORM\Column(type="date" , nullable=true)
     * @var \DateTime
     */
    public $modified = null;

    /**
     * Get id
     * 
     * 
     * @access public
     * @return int id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get title
     * 
     * 
     * @access public
     * @return string title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     * 
     * 
     * @access public
     * @param string $title
     * @return Page current entity
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get path
     * 
     * 
     * @access public
     * @return string path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path
     * 
     * 
     * @access public
     * @param string $path
     * @return Page current entity
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get type
     * 
     * 
     * @access public
     * @return string type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     * 
     * 
     * @access public
     * @param string $type
     * @return Page current entity
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get category
     * 
     * 
     * @access public
     * @return string category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set category
     * 
     * 
     * @access public
     * @param string $category
     * @return Page current entity
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get author
     * 
     * 
     * @access public
     * @return string author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set author
     * 
     * 
     * @access public
     * @param string $author
     * @return Page current entity
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get picture
     * 
     * 
     * @access public
     * @return string picture
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set picture
     * 
     * 
     * @access public
     * @param string $picture
     * @return Page current entity
     */
    public function setPicture($picture)
    {
        if(is_array($picture) && array_key_exists("tmp_name", $picture)){
            $picture["tmp_name"] = str_replace(/* $search= */APPLICATION_PATH, /* $replace= */'', $picture["tmp_name"]);
        }
        $this->picture = $picture;
        return $this;
    }

    /**
     * Get summary
     * 
     * 
     * @access public
     * @return string summary
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set summary
     * 
     * 
     * @access public
     * @param string $summary
     * @return Page current entity
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * Get body
     * 
     * 
     * @access public
     * @return string body
     */
    public function getBody()
    {
        return bzdecompress(base64_decode($this->body));
    }

    /**
     * Set body
     * 
     * 
     * @access public
     * @param string $body
     * @return Page current entity
     */
    public function setBody($body)
    {
        // compress large page content
        // encode data, so that binary data survive transport through transport layers that are not 8-bit clean
        $this->body = base64_encode(bzcompress($body));
        return $this;
    }

    /**
     * Get status
     * 
     * 
     * @access public
     * @return int status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     * 
     * 
     * @access public
     * @param int $status
     * @return MenuItem current entity
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
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

    /**
     * Set created
     * 
     * @ORM\PrePersist
     * @access public
     * @return Page current entity
     */
    public function setCreated()
    {
        $this->created = new \DateTime();
        return $this;
    }

    /**
     * Get modified
     * 
     * 
     * @access public
     * @return \DateTime modified
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set modified
     * 
     * @ORM\PreUpdate
     * @access public
     * @return Page current entity
     */
    public function setModified()
    {
        $this->modified = new \DateTime();
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
        $this->setType($data["type"]);
        if (array_key_exists('title', $data)) {
            $this->setTitle($data["title"]);
        }
        if (array_key_exists('path', $data)) {
            $this->setPath($data["path"]);
        }
        if (array_key_exists('body', $data)) {
            $this->setBody($data["body"]);
        }
        if (array_key_exists('status', $data)) {
            $this->setStatus($data["status"]);
        }
        if (array_key_exists('category', $data) && !empty($data["category"])) {
            $this->setCategory($data["category"]);
        }
        if (array_key_exists('author', $data) && !empty($data["author"])) {
            $this->setAuthor($data["author"]);
        }
        if (array_key_exists('picture', $data) && !empty($data["picture"]["name"])) {
            $this->setPicture($data["picture"]);
        }
        if (array_key_exists('summary', $data) && !empty($data["summary"])) {
            $this->setSummary($data["summary"]);
        }
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
     * @param Utilities\Service\Query\Query $query
     * @return InputFilter validation constraints
     */
    public function getInputFilter($query)
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'title',
                'required' => true
            ));
            $inputFilter->add(array(
                'name' => 'path',
                'required' => true
            ));
            
            $pageTypesReflection = new \ReflectionClass('CMS\Service\PageTypes');
            $inputFilter->add(array(
                'name' => 'type',
                'required' => true,
                'validators' => array(
                    array('name' => 'InArray',
                        'options' => array(
                            'haystack' => $pageTypesReflection->getConstants(),
                        )
                    ),
                )
            ));
            $pageCategoriesReflection = new \ReflectionClass('CMS\Service\PageCategories');
            $inputFilter->add(array(
                'name' => 'category',
                'required' => true,
                'validators' => array(
                    array('name' => 'InArray',
                        'options' => array(
                            'haystack' => $pageCategoriesReflection->getConstants(),
                        )
                    ),
                )
            ));
            $inputFilter->add(array(
                'name' => 'author',
                'required' => true
            ));
            
            $DirSep = DIRECTORY_SEPARATOR;
            $targetPath = APPLICATION_PATH . $DirSep . 'upload' . $DirSep . 'pagePictures' . $DirSep ;
            if (!file_exists($targetPath)) {
                $oldUmask = umask(0);
                mkdir($targetPath, 0777);
                umask($oldUmask);
            }
            $random = new Random();
            $fileUploadOptions = array(
                "target" => $targetPath . $random->getRandomUniqueName(),
                "overwrite" => true,
                "use_upload_name" => false,
                "use_upload_extension" => true
            );
            $inputFilter->add(array(
                'name' => 'picture',
                'required' => true,
                'filters' => array(
                    array(
                        "name" => "Zend\Filter\File\RenameUpload",
                        "options" => $fileUploadOptions
                    ),
                ),
                'validators' => array(
                    array('name' => 'Zend\Validator\File\IsImage',
                    ),
                )
            ));
            $inputFilter->add(array(
                'name' => 'summary',
                'required' => true,
                'validators' => array(
                    array('name' => 'StringLength',
                        'options' => array(
                            'max' => 1000, 
                            'encoding' => 'UTF-8'
                        )
                    ),
                )
            ));
            $inputFilter->add(array(
                'name' => 'body',
                'required' => true,
                'validators' => array(
                    array('name' => 'CMS\Service\Validator\NotEmptyTrimmed',
                    ),
                )
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
