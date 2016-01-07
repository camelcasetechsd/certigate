<?php

namespace CMS\Model;

use Utilities\Service\Inflector;

/**
 * MenuItem Model
 * 
 * Handles MenuItem Entity related business
 * 
 * @property Utilities\Service\Inflector $inflector
 * 
 * @package cms
 * @subpackage model
 */
class MenuItem {

     /**
     *
     * @var Utilities\Service\Inflector
     */
    protected $inflector;

    /**
     * Set needed properties
     * 
     * @access public
     */
    public function __construct() {
        $this->inflector = new Inflector();
    }
    /**
     * Recursive menu items by parents branch extrusion
     * 
     * @access public
     * @param array $menuItemsByParents
     * @param array $menuItemsPerParent
     * @param bool $treeFlag ,default is false
     * 
     * @return array parent children with children appended under it
     */
    public function sortMenuItemsByParents(&$menuItemsByParents, &$menuItemsPerParent, $treeFlag = false) {
        $tree = array();
        foreach ($menuItemsPerParent as $menuItem) {
            $menuItem->children = array();
            if (isset($menuItemsByParents[$menuItem->getId()])) {
                // get all children under menu item
                $menuItem->children = $this->sortMenuItemsByParents($menuItemsByParents, $menuItemsByParents[$menuItem->getId()], $treeFlag);
            }
            if($treeFlag === false){
                $tree[] = $menuItem;
                // append children under direct parent
                if (count($menuItem->children) > 0) {
                    $tree = array_merge($tree, $menuItem->children);
                    unset($menuItem->children);
                }
            }else{
                $menuItemTitle = $menuItem->getTitle();
                $menuTitle = $menuItem->getMenu()->getTitle();
                $menuItemArray = array(
                    'path' => $menuItem->getPath(),
                    'weight' => $menuItem->getWeight(),
                    'title' => $menuItemTitle,
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
    public function getSortedMenuItems($menuItems, $root = 0, $treeFlag = false) {
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

}
