<?php

namespace CMS\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Utilities\Service\Status;

/**
 * MenuItem Entity
 * @ORM\Entity(repositoryClass="CMS\Entity\MenuItemRepository")
 * @ORM\Table(name="menuItem")
 * @ORM\HasLifecycleCallbacks
 * 
 * @property InputFilter $inputFilter validation constraints 
 * @property int $id
 * @property string $title
 * @property int $type
 * @property string $directUrl
 * @property CMS\Entity\Page $page
 * @property CMS\Entity\Menu $menu
 * @property CMS\Entity\MenuItem $parent
 * @property int $weight
 * @property int $status
 * @property \DateTime $created
 * @property \DateTime $modified
 * 
 * @package cms
 * @subpackage entity
 */
class MenuItem
{

    /**
     * Separator between menu and menu item titles
     */
    const MENU_ITEM_TITLE_SEPARATOR = "%^*";

    /**
     * Menu Item Types
     */
    const TYPE_PAGE = 1;
    const TYPE_DIRECT_URL = 2;

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
     * @var string
     */
    public $title;

    /**
     * 
     * @ORM\Column(type="integer")
     * @var ineteger
     */
    public $type;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    public $directUrl;

    /**
     *
     * @ORM\ManyToOne(targetEntity="CMS\Entity\Page")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=true)
     * @var CMS\Entity\Page
     */
    public $page;

    /**
     *
     * @ORM\ManyToOne(targetEntity="CMS\Entity\Menu")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     * @var CMS\Entity\Menu
     */
    public $menu;

    /**
     *
     * @ORM\ManyToOne(targetEntity="CMS\Entity\MenuItem")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id" , nullable=true)
     * @var CMS\Entity\MenuItem
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
    public $status;

    /**
     *
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    public $created;

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
     * @return MenuItem current entity
     */
    public function setTitle( $title )
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get type
     * 
     * 
     * @access public
     * @return int type
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
     * @param int $type
     * @return MenuItem current entity
     */
    public function setType( $type )
    {
        $this->type = $type;
        return $this;
    }
    
    /**
     * Get directUrl
     * 
     * 
     * @access public
     * @return string diectUrl
     */
    public function getDirectUrl()
    {
        return $this->directUrl;
    }

    /**
     * Set direct url
     * 
     * 
     * @access public
     * @param string directUrl 
     * @return MenuItem current entity
     */
    public function setDirectUrl( $directUrl )
    {
        $this->directUrl = $directUrl;
        return $this;
    }

    /**
     * Get page
     * 
     * 
     * @access public
     * @return CMS\Entity\Page page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set page
     * 
     * 
     * @access public
     * @param CMS\Entity\Page $page
     * @return MenuItem current entity
     */
    public function setPage( $page )
    {
        $this->page = $page;
        return $this;
    }

    /**
     * Get menu
     * 
     * 
     * @access public
     * @return CMS\Entity\Menu menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set menu
     * 
     * 
     * @access public
     * @param CMS\Entity\Menu $menu
     * @return MenuItem current entity
     */
    public function setMenu( $menu )
    {
        $this->menu = $menu;
        return $this;
    }

    /**
     * Get parent
     * 
     * 
     * @access public
     * @return CMS\Entity\MenuItem parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     * 
     * 
     * @access public
     * @param CMS\Entity\MenuItem $parent
     * @return MenuItem current entity
     */
    public function setParent( $parent )
    {
        if (empty( $parent )) {
            $parent = null;
        }
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get weight
     * 
     * 
     * @access public
     * @return int weight
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set weight
     * 
     * 
     * @access public
     * @param int $weight
     * @return MenuItem current entity
     */
    public function setWeight( $weight )
    {
        $this->weight = $weight;
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
    public function setStatus( $status )
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
     * @return MenuItem current entity
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
     * @return MenuItem current entity
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
        return get_object_vars( $this );
    }

    /**
     * Get depth level.
     * Minimum depth level is one for menuitem with root parent
     * 
     * @access public
     * @return int depth level
     */
    public function getDepthLevel()
    {
        $depthLevel = 1;
        $menuItem = $this;
        while ($menuItem->getParent() instanceof MenuItem) {
            $depthLevel++;
            $menuItem = $menuItem->getParent();
        }
        return $depthLevel;
    }

    /**
     * Get nested title.
     * get menu item title with depth level apparent in display
     * 
     * @access public
     * @return string nested title
     */
    public function getNestedTitle()
    {
        $menu = $this->getMenu();
        $nestedTitle = '';
        if (is_object( $menu )) {
            $nestedTitle .= $menu->getId() . self::MENU_ITEM_TITLE_SEPARATOR . $menu->getTitle() . self::MENU_ITEM_TITLE_SEPARATOR;
        }
        $nestedTitle .= str_repeat( '- ', $this->getDepthLevel() ) . $this->getTitle();
        if ($this->getStatus() === Status::STATUS_INACTIVE || (is_object( $menu ) && $menu->getStatus() === Status::STATUS_INACTIVE)) {
            $nestedTitle .= ' [' . Status::STATUS_INACTIVE_TEXT . ']';
        }
        return $nestedTitle;
    }

    /**
     * Populate from an array.
     * 
     * 
     * @access public
     * @param array $data ,default is empty array
     */
    public function exchangeArray( $data = array() )
    {
        if (array_key_exists( 'title', $data )) {
            $this->setTitle( $data["title"] );
        }
        if (array_key_exists( 'type', $data )) {
            $this->setType( $data["type"] );
        }
        if (array_key_exists( 'directUrl', $data )) {
            $this->setDirectUrl( $data["directUrl"] );
        }
        if (array_key_exists( 'page', $data ) && !empty($data['page'])) {
            $this->setPage( $data["page"] );
        }
        if (array_key_exists( 'menu', $data )) {
            $this->setMenu( $data["menu"] );
        }
        if (array_key_exists( 'parent', $data )) {
            $this->setParent( $data["parent"] );
        }
        if (array_key_exists( 'weight', $data )) {
            $this->setWeight( $data["weight"] );
        }
        if (array_key_exists( 'status', $data )) {
            $this->setStatus( $data["status"] );
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
    public function setInputFilter( InputFilterInterface $inputFilter )
    {
        throw new \Exception( "Not used" );
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
    public function getInputFilter( $query )
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add( array(
                'name' => 'title',
                'required' => true
            ) );
            $inputFilter->add( array(
                'name' => 'type',
                'required' => true
            ) );
            $inputFilter->add( array(
                'name' => 'directUrl',
                'required' => FALSE
            ) );
            $inputFilter->add(array(
                'name' => 'page',
                'required' => FALSE,
            ));
            $inputFilter->add( array(
                'name' => 'menu',
                'required' => true
            ) );
            $inputFilter->add( array(
                'name' => 'weight',
                'required' => true,
                'validators' => array(
                    array('name' => 'GreaterThan',
                        'options' => array(
                            'min' => 0,
                            'inclusive' => false
                        )
                    ),
                    array(
                        'name' => 'Digits',
                    ),
                )
            ) );
            $inputFilter->add( array(
                'name' => 'parent',
                'required' => false
            ) );
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
