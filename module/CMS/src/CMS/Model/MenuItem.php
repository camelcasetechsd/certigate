<?php

namespace CMS\Model;

/**
 * MenuItem Model
 * 
 * Handles MenuItem Entity related business
 * 
 * 
 * @package cms
 * @subpackage model
 */
class MenuItem {

    /**
     * Recursive menu items by parents branch extrusion
     * 
     * @access public
     * @param array $menuItemsByParents
     * @param array $menuItemsPerParent
     * @return array parent children with children appended under it
     */
    public function sortMenuItemsByParents(&$menuItemsByParents, &$menuItemsPerParent) {
        $tree = array();
        foreach ($menuItemsPerParent as $menuItem) {
            if (isset($menuItemsByParents[$menuItem->getId()])) {
                // get all children under menu item
                $menuItem->children = $this->sortMenuItemsByParents($menuItemsByParents, $menuItemsByParents[$menuItem->getId()]);
            }
            $tree[] = $menuItem;
            // append children under direct parent
            if (isset($menuItem->children)) {
                $tree = array_merge($tree, $menuItem->children);
                unset($menuItem->children);
            }
        }
        return $tree;
    }

    /**
     * Get menu items sorted according to menu and menu items' parents
     * 
     * @access public
     * @param array $menuItems
     * @param int $root
     * @return array
     */
    public function getSortedMenuItems($menuItems, $root = 0) {
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
        return $this->sortMenuItemsByParents($menuItemsByParents, $menuItemsByParents[$root]);
    }

}
