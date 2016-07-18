<?php

namespace CMS\Model;

use Utilities\Service\Inflector;
use Utilities\Service\Query\Query;
use Utilities\Service\Paginator\PaginatorAdapter;
use Zend\Paginator\Paginator;
use Doctrine\Common\Collections\Criteria;
use CMS\Entity\MenuItem as MenuItemEntity;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Users\Entity\Role;

/**
 * MenuItem Model
 * 
 * Handles MenuItem Entity related business
 * 
 * @property Utilities\Service\Inflector $inflector
 * @property Query $query
 * @property array $staticMenus
 *  
 * @package cms
 * @subpackage model
 */
class MenuItem implements ServiceLocatorAwareInterface
{

    use \Utilities\Service\Paginator\PaginatorTrait;

    /**
     *
     * @var Utilities\Service\Inflector
     */
    protected $inflector;

    /**
     *
     * @var Query 
     */
    protected $query;

    /**
     * @var array
     */
    protected $staticMenus;

    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Query $query ,default is null
     * @param array $staticMenus ,default is empty array
     */
    public function __construct($query = null, $staticMenus = array())
    {
        $this->inflector = new Inflector();
        $this->query = $query;
        $this->paginator = new Paginator(new PaginatorAdapter($query, "CMS\Entity\MenuItem"));
        $this->staticMenus = $staticMenus;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Recursive menu items by parents branch extrusion
     * 
     * @access public
     * @param array $menuItemsByParents
     * @param array $menuItemsPerParent
     * @param bool $treeFlag ,default is false
     * @param int $depthLevel ,default is 0
     * 
     * @return array parent children with children appended under it
     */
    public function sortMenuItemsByParents(&$menuItemsByParents, &$menuItemsPerParent, $treeFlag = false, $depthLevel = 0)
    {
        $tree = array();
        foreach ($menuItemsPerParent as $menuItem) {
            $menuItem->children = array();
            if (isset($menuItemsByParents[$menuItem->getId()])) {
                $depthLevel++;
                // get all children under menu item
                $menuItem->children = $this->sortMenuItemsByParents($menuItemsByParents, $menuItemsByParents[$menuItem->getId()], $treeFlag, $depthLevel);
                $depthLevel--;
            }

            if ($treeFlag === false) {
                $tree[] = $menuItem;
                // append children under direct parent
                if (count($menuItem->children) > 0) {
                    $tree = array_merge($tree, $menuItem->children);
                    unset($menuItem->children);
                }
            }
            else {
                $applicationLocale = $this->getServiceLocator()->get('applicationLocale');
                $currentLocale = $applicationLocale->getCurrentLocale();
                if ($currentLocale == \Translation\Service\Locale\Locale::LOCALE_AR_AR) {
                    $menuItemTitle = $menuItem->getTitleAr();
                }
                else if ($currentLocale == \Translation\Service\Locale\Locale::LOCALE_EN_US) {
                    $menuItemTitle = $menuItem->getTitle();
                }

                $menuTitle = $this->inflector->underscore($menuItem->getMenu()->getTitle());
                if ($menuItem->getType() == MenuItemEntity::TYPE_PAGE && is_object($menuItem->getPage())) {
                    $path = $menuItem->getPage()->getPath();
                }
                else {
                    $path = $menuItem->getDirectUrl();
                }
                $menuItemArray = array(
                    'depth' => $depthLevel,
                    'path' => $path,
                    'weight' => $menuItem->getWeight(),
                    'title_underscored' => $this->inflector->underscore($menuItemTitle),
                    'children' => $menuItem->children
                );
                $tree[$menuTitle][$menuItemTitle] = $menuItemArray;
            }
        }
        return $tree;
    }

    /**
     * Get menu items sorted according to menu and menu items' parents
     * Prepare manage menu
     * 
     * @access public
     * @param array $menuItems
     * @param int $root ,default is 0
     * @param bool $treeFlag ,default is false
     * 
     * @return array menu items sorted
     */
    public function getSortedMenuItems($menuItems, $root = 0, $treeFlag = false)
    {
        $menuItemsByParents = array(
            $root => array()
        );
        foreach ($menuItems as $menuItem) {
            $menuItemParentId = $root;
            if (!is_null($menuItem->getParent())) {
                $menuItemParentId = $menuItem->getParent()->getId();
            }
            $menuItemsByParents[$menuItemParentId][] = $menuItem;
        }
        $sortedMenuItems = $this->sortMenuItemsByParents($menuItemsByParents, $menuItemsByParents[$root], $treeFlag);

        return $sortedMenuItems;
    }

    /**
     * Get manage menu items after merging all logged in user roles
     * 
     * @access public
     * @param array $sortedMenuItems
     * @param array $roles logged in user roles
     * @param string $userName
     * @return array manage menu items
     */
    public function getManageMenuItems($sortedMenuItems, $roles, $userName)
    {
        $manageMenuItems = reset($sortedMenuItems[$this->inflector->underscore(Role::USER_ROLE)][Role::USER_ROLE]["children"]);
        foreach ($roles as $role) {
            if ($role == Role::USER_ROLE) {
                continue;
            }
            $roleUnderscored = $this->inflector->underscore($role);
            if (array_key_exists($roleUnderscored, $sortedMenuItems)) {
                $manageMenuItems = array_merge($manageMenuItems, reset($sortedMenuItems[$roleUnderscored][$role]["children"]));
            }
        }
        $manageMenu = array(
            $userName => array(
                "title" => $userName,
                "titleAr" => $userName,
                "path" => "#",
                "weight" => 1,
                "depth" => 0,
                "title_underscored" => $this->inflector->underscore($userName),
                "children" => array($manageMenuItems)
            )
        );
        return $manageMenu;
    }

    /**
     * Get menu items sorted according to menu and menu items' parents
     * Merge static menus with dynamic ones
     * Merged result is reordered according to first level menu items' weights
     * 
     * @access public
     * @param bool $includeStatic ,default is true
     * 
     * @return array all menus sorted
     * @throws Exception query must be instance of Query
     */
    public function getMenuItems($includeStatic = true)
    {
        if (!$this->query instanceof Query) {
            throw new Exception("query must be instance of Query");
        }
        // get dynamic menus
        $menuItems = $this->getMenuItemReporsitory()->getMenuItemsSorted(/* $hiddenMenuItemsIds = */ array(), /* $menuItemStatus = */ true, /* $menuStatus = */ true, /* $withPagesOnlyFlag = */ false, /* $select = */ null, /* $treeFlag = */ true);
        if ($includeStatic === true) {
            $weight = array();
            // merge static and dynamic menus
            foreach ($this->staticMenus as $staticMenuTitle => $staticMenuArray) {
                if (array_key_exists($staticMenuTitle, $menuItems)) {
                    $menuItems[$staticMenuTitle] = array_merge($staticMenuArray, $menuItems[$staticMenuTitle]);
                }
                else {
                    $menuItems[$staticMenuTitle] = $staticMenuArray;
                }
            }
            foreach ($menuItems as $menuTitle => $menuArray) {
                $weight[$menuTitle] = array();
                $sortedMenuArray = array();
                // collect menu weights
                foreach ($menuArray as $menuItemTitle => $menuItemArray) {
                    $weight[$menuTitle][] = $menuItemArray['weight'];
                }
                // reorder weights per menu
                asort($weight[$menuTitle]);
                // reoreder Merged result according to first level menu items' weights
                foreach ($weight[$menuTitle] as $menuItemWeight) {
                    foreach ($menuArray as $menuItemTitle => $menuItemArray) {
                        if ($menuItemArray['weight'] === $menuItemWeight) {
                            $sortedMenuArray[$menuItemTitle] = $menuItemArray;
                        }
                    }
                }
                $menuItems[$menuTitle] = $sortedMenuArray;
            }
        }
        return $menuItems;
    }

    /**
     * Filter menu items
     * 
     * @access public
     * @param array $data filter data
     */
    public function filterMenuItems($data)
    {
        $menuItemFilterFields = array(
            "type",
            "title",
            "directUrl",
            "menu",
            "status",
        );
        $menuItemFieldsFilterByLike = array(
            "title",
            "directUrl",
        );
        $data = array_intersect_key($data, array_flip($menuItemFilterFields));
        if (!empty($data["menu"])) {
            $data["menu"] = $this->query->find('CMS\Entity\Menu', $data["menu"]);
        }
        $criteria = Criteria::create();
        $expr = Criteria::expr();
        foreach ($data as $fieldName => $fieldValue) {
            // only submitted values are used in filter
            if ($fieldValue != "") {
                $expressionMethod = "eq";
                if (in_array($fieldName, $menuItemFieldsFilterByLike)) {
                    $expressionMethod = "contains";
                }
                $criteria->andWhere($expr->$expressionMethod($fieldName, $fieldValue));
            }
        }
        $this->setCriteria($criteria);
    }

    /**
     * Set menu item form required fields
     * 
     * @access public
     * @param Zend\Form\FormInterface $form
     * @param array $data
     */
    public function setFormRequiredFields($form, $data)
    {
        $inputFilter = $form->getInputFilter();
        if (isset($data['type']) && $data['type'] == MenuItemEntity::TYPE_DIRECT_URL) {
            // Change required flag to false
            $input = $inputFilter->get('page');
            $input->setRequired(false);
        }
        if (isset($data['type']) && $data['type'] == MenuItemEntity::TYPE_PAGE) {
            // Change required flag to false
            $input = $inputFilter->get('directUrl');
            $input->setRequired(false);
        }
    }

    /**
     * 
     * @return \CMS\Entity\MenuItemRepository
     */
    public function getMenuItemReporsitory()
    {
        $menuItemRepository = $this->query->setEntity(/* $entityName = */ 'CMS\Entity\MenuItem')->entityRepository;
        $menuItemRepository->setServiceLocator($this->getServiceLocator());
        return $menuItemRepository;
    }

}
