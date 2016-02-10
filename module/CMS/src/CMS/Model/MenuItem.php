<?php

namespace CMS\Model;

use Utilities\Service\Inflector;
use Utilities\Service\Query\Query;
use Utilities\Service\Paginator\PaginatorAdapter;
use Zend\Paginator\Paginator;
use Doctrine\Common\Collections\Criteria;
use CMS\Entity\MenuItem as MenuItemEntity;

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
class MenuItem
{

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
     * @var Zend\Paginator\Paginator 
     */
    protected $paginator = NULL;

    /**
     *
     * @var int 
     */
    protected $numberPerPage = 10.0;

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
                $menuItemTitle = $menuItem->getTitle();
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
        return $this->sortMenuItemsByParents($menuItemsByParents, $menuItemsByParents[$root], $treeFlag);
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
        $menuItems = $this->query->setEntity(/* $entityName = */ 'CMS\Entity\MenuItem')->entityRepository->getMenuItemsSorted(/* $hiddenMenuItemsIds = */ array(), /* $menuItemStatus = */ true, /* $menuStatus = */ true, /* $withPagesOnlyFlag = */ false, /* $select = */ null, /* $treeFlag = */ true);
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
     * Set current page
     * @param int $currentPage
     */
    public function setPage($currentPage)
    {
        $this->paginator->setCurrentPageNumber($currentPage);
    }

    /**
     * Set number of pages
     * @param int $numberPerPage
     */
    public function setNumberPerPage($numberPerPage)
    {
        $this->numberPerPage = $numberPerPage;
    }

    /**
     * Set number of items per page
     * @param int $itemsCountPerPage
     */
    public function setItemCountPerPage($itemsCountPerPage)
    {
        $this->paginator->setItemCountPerPage($itemsCountPerPage);
    }

    /**
     * Get number of pages
     * @return int number of pages
     */
    public function getNumberOfPages()
    {
        return (int) $this->paginator->count();
    }

    /**
     * Get pages range
     * 
     * @access public
     * 
     * @param int $currentPageNumber ,default is null
     * @return array pages range
     */
    public function getPagesRange($currentPageNumber = null)
    {

        $numberOfPages = $this->getNumberOfPages();
        $pageNumbers = array();
        //create an array of page numbers
        if ($numberOfPages > 1) {
            $pageNumbers = range(1, $numberOfPages);


            foreach ($pageNumbers as $pageKey => &$pageNumber) {
                $pageNumber = array(
                    "pageNumber" => $pageKey + 1,
                    "isCurrent" => false
                );
            }

            if (empty($currentPageNumber)) {
                $currentPageNumber = 1;
            }
            $pageNumbers[$currentPageNumber - 1]["isCurrent"] = true;
        }
        return $pageNumbers;
    }

    /**
     * Get next page number
     * 
     * @access public
     * 
     * @param int $currentPageNumber ,default is null
     * @return int next page number
     */
    public function getNextPageNumber($currentPageNumber = null)
    {
        $nextPageNumber = 0;
        $numberOfPages = $this->getNumberOfPages();
        if ($numberOfPages > 1) {
            if (!empty($currentPageNumber)) {
                if ($currentPageNumber != $numberOfPages) {
                    $nextPageNumber += $currentPageNumber + 1;
                }
            }
            else {
                $nextPageNumber = 2;
            }
        }

        return $nextPageNumber;
    }

    /**
     * Get previous page number
     * 
     * @access public
     * 
     * @param int $currentPageNumber ,default is null
     * @return int previous page number
     */
    public function getPreviousPageNumber($currentPageNumber = null)
    {
        $previousPageNumber = 0;
        $numberOfPages = $this->getNumberOfPages();
        if ($numberOfPages > 1 && !empty($currentPageNumber)) {
            $previousPageNumber += $currentPageNumber - 1;
        }

        return $previousPageNumber;
    }

    /**
     * Get number of items per page
     * @return int number of items per page
     */
    public function getCurrentItems()
    {
        return $this->paginator;
    }

    /**
     * Set criteria
     * 
     * @access public
     * 
     * @param Doctrine\Common\Collections\Criteria $criteria
     */
    public function setCriteria($criteria)
    {
        $this->paginator->getAdapter()->setCriteria($criteria);
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
            "title",
            "directUrl",
            "menu",
            "status",
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
                $criteria->andWhere($expr->eq($fieldName, $fieldValue));
            }
        }
        $this->setCriteria($criteria);
    }

}
